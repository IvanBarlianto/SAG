pipeline {
    agent any
    
    environment {
        PATH = "C:/Program Files/7-Zip:$PATH"
    }
    
    stages {
        stage("Verify tooling") {
            steps {
                script {
                    bat '''
                        docker info
                        docker version
                        docker-compose version
                        terraform --version
                    '''
                }
            }
        }
        
        stage("Clear all running docker containers") {
            steps {
                script {
                    try {
                        bat 'docker rm -f $(docker ps -aq)'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
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
        
        stage("Terraform Apply") {
            steps {
                script {
                    bat 'terraform apply -auto-approve'
                    bat 'terraform output -json > tf-output.json'
                }
            }
        }
        
        stage("Parse Terraform Output") {
            steps {
                script {
                    def output = readJSON file: 'tf-output.json'
                    env.PUBLIC_IP = output.public_ip.value
                }
            }
        }
        
        stage("Verify SSH connection to server") {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                    bat '''
                        ssh -i "${SSH_KEY}" -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami
                    '''
                }
            }
        }
        
        stage("Start Docker") {
            steps {
                script {
                    bat 'docker-compose up -d'
                    bat 'docker-compose ps'
                }
            }
        }
        
        stage("Run Composer Install") {
            steps {
                script {
                    bat 'docker-compose run --rm composer install'
                }
            }
        }
        
        stage("Populate .env file") {
            steps {
                script {
                    bat "xcopy /s /y C:/ProgramData/Jenkins/.jenkins/workspace/envs/sag/.env ${WORKSPACE}"
                }
            }
        }
        
        stage("Run Tests") {
            steps {
                script {
                    bat 'echo running unit-tests'
                    bat 'docker-compose run --rm artisan test'
                }
            }
        }
    }
    
    post {
        success {
            script {
                bat '''
                    cd C:/ProgramData/Jenkins/.jenkins/workspace/sag
                    del /q artifact.zip
                    7z a -r -tzip artifact.zip * -x!node_modules/*
                '''
                withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                    bat '''
                        scp -v -o StrictHostKeyChecking=no -i "${SSH_KEY}" artifact.zip ubuntu@13.211.134.87:/home/ubuntu/artifact
                    '''
                }
            }
        }
        
        always {
            script {
                bat 'docker-compose down --remove-orphans -v'
                bat 'docker-compose ps'
            }
        }
    }
}
