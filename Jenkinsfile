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
        
        stage("Transfer Artifact") {
            steps {
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
        }
    }
    
    post {
        success {
            script {
                bat 'terraform destroy -auto-approve'
                bat 'docker-compose down --remove-orphans -v'
                bat 'docker-compose ps'
            }
        }
        
        failure {
            script {
                bat 'terraform destroy -auto-approve'
                bat 'docker-compose down --remove-orphans -v'
                bat 'docker-compose ps'
            }
        }
        
        always {
            script {
                bat 'terraform destroy -auto-approve'
                bat 'docker-compose down --remove-orphans -v'
                bat 'docker-compose ps'
            }
        }
    }
}
