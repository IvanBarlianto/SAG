pipeline {
    agent any
    
    environment {
        PATH = "C:/Program Files/7-Zip:$PATH"
        SONARQUBE_URL = 'http://localhost/:9000' // Adjust the URL if SonarQube is running on a different port or host
        SONARQUBE_LOGIN = credentials('sonar_sag')
    }
    
    stages {
        stage("Verify tooling") {
            steps {
                bat '''
                    docker info
                    docker version
                    docker-compose version
                    terraform --version
                '''
            }
        }
        
        
        stage("Verify SSH connection to server") {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                    bat '''
                        "C:/Program Files/Git/bin/bash.exe" -c "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@3.106.227.133 whoami"
                    '''
                }
            }
        }
        
        stage("Start Docker") {
            steps {
                bat 'docker-compose up -d'
                bat 'docker-compose ps'
            }
        }
        
        stage("Run Composer Install") {
            steps {
                bat 'docker-compose run --rm composer install'
            }
        }
        
        stage("Populate .env file") {
            steps {
                dir("C:/ProgramData/Jenkins/.jenkins/workspace/envs/sag") {
                    fileOperations([fileCopyOperation(excludes: '', flattenFiles: true, includes: '.env', targetLocation: "${WORKSPACE}")])
                }
            }
        }
         stage("Terraform Init") {
            steps {
                script {
                    bat 'terraform init'
                }
            }
        }
        
        stage("Terraform Plan") {
            steps {
                script {
                    bat 'terraform plan -out=tfplan'
                }
            }
        }
        
        stage("Terraform Apply") {
            steps {
                script {
                    bat 'terraform apply -auto-approve tfplan'
                    bat 'terraform output -json > tf-output.json'
                }
            }
        }
        
        stage('SonarQube analysis') {
            steps {
                script {
                    bat 'sonar-scanner'
                }
            }
        }
        
        stage("Run Tests") {
            steps {
                bat 'echo running unit-tests'
                bat 'docker-compose run --rm artisan test'
            }
        }
    }
    
    post {
        success {
            bat '''
                cd C:/ProgramData/Jenkins/.jenkins/workspace/sag
                rm -rf artifact.zip
                7z a -r -tzip artifact.zip * -x!node_modules/*
            '''
            withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                bat'''
                    "C:/Program Files/Git/bin/bash.exe" -c "scp -v -o StrictHostKeyChecking=no -i ${SSH_KEY} C:/ProgramData/Jenkins/.jenkins/workspace/sag/artifact.zip ubuntu@3.106.227.133:/home/ubuntu/artifact"
                '''
            }
        }
        
        failure {
            script {
                bat 'terraform destroy -auto-approve'
                bat 'docker-compose ps'
            }
        }
        
        always {
            script {
                bat 'terraform destroy -auto-approve'
                bat 'docker-compose ps'
            }
        }
    }
}
