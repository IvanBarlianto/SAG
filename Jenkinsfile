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
                '''
            }
        }
        stage("Verify SSH connection to server") {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                    bat '''
                        "C:/Program Files/Git/bin/bash.exe" -c "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.55.70.75 whoami"
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
        stage("Run Tests") {
            steps {
                bat 'echo running unit-tests'
                bat 'docker-compose run --rm artisan test'
            }
        }
        stage('SonarQube analysis') {
            steps {
                bat 'sonar-scanner'
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
                    "C:/Program Files/Git/bin/bash.exe" -c "scp -v -o StrictHostKeyChecking=no -i ${SSH_KEY} C:/ProgramData/Jenkins/.jenkins/workspace/sag/artifact.zip ubuntu@13.55.70.75:/home/ubuntu/artifact"
                '''
            }
            withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                bat '''
                    "C:/Program Files/Git/bin/bash.exe" -c "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.55.70.75 'unzip -o /home/ubuntu/artifact/artifact.zip -d /var/www/html/SAG'"
                '''
                script {
                    try {
                        bat '''
                            "C:/Program Files/Git/bin/bash.exe" -c "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.55.70.75 sudo chmod 777 /var/www/html/SAG/storage -R"
                        '''
                    } catch (Exception e) {
                        echo 'Some file permissions could not be updated.'
                    }
                }
            }
        }
        always {
            sh 'docker compose ps'
        }
    }
}
