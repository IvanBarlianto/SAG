pipeline {
    agent any
    environment {
<<<<<<< HEAD
        SSH_CREDENTIALS_ID = 'aws-ec2' // Replace with your actual SSH credential ID
=======
        PATH = "C:/Program Files/7-Zip:$PATH"
>>>>>>> Ivan
    }
    stages {
        stage("Verify tooling") {
            steps {
                bat '''
                    docker info
                    docker version
                    docker-compose version
                '''
            }
        }
        stage("Clear all running docker containers") {
            steps {
                script {
                    try {
<<<<<<< HEAD
                        bat 'docker volume rm -f'
=======
                        // Jalankan perintah bat untuk membersihkan kontainer Docker
                        bat 'for /f "tokens=*" %%i in (\'docker ps -aq\') do docker rm -f %%i'
>>>>>>> Ivan
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage('Run SSH Command') {
            steps {
<<<<<<< HEAD
                script {
                    def sshCommand = "ssh -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami"
                    withCredentials([sshUserPrivateKey(credentialsId: SSH_CREDENTIALS_ID, keyFileVariable: 'SSH_KEY')]) {
                        bat "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami"
                    }
=======
                withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                    bat '''
                        "C:/Program Files/Git/bin/bash.exe" -c "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami"
                    '''
>>>>>>> Ivan
                }
            }
        }
        stage("Start Docker") {
            steps {
                bat 'make up'
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
        stage("Run Tests") {
            steps {
                bat 'echo running unit-tests'
                bat 'docker-compose run --rm artisan test'
            }
        }
    }
<<<<<<< HEAD
}
=======
    post {
        success {
            bat '''
                cd C:/ProgramData/Jenkins/.jenkins/workspace/sag
                rm -rf artifact.zip
                7z a -r -tzip artifact.zip * -x!node_modules/*
            '''
            withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                bat'''
                    "C:/Program Files/Git/bin/bash.exe" -c "scp -v -o StrictHostKeyChecking=no -i ${SSH_KEY} C:/ProgramData/Jenkins/.jenkins/workspace/sag/artifact.zip ubuntu@13.211.134.87:/home/ubuntu/artifact"
                '''
            }
        }
        always {
            sh 'docker compose down --remove-orphans -v'
            sh 'docker compose ps'
        }
    }
}
>>>>>>> Ivan
