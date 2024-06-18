pipeline {
    agent any
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
                        // Ambil daftar ID kontainer Docker
                        def containerIds = bat(script: 'docker ps -aq', returnStdout: true).trim().split('\r?\n')
                        // Perulangan untuk menghapus setiap kontainer
                        containerIds.each { id ->
                            bat "docker rm -f $id"
                        }
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage("Verify SSH connection to server") {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: 'sag-aws-key', keyFileVariable: 'SSH_KEY')]) {
                    bat '''
                        "C:/Program Files/Git/bin/bash.exe" -c "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami"
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
    }
}
