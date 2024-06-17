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
                        bat 'docker volume rm -f'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage("Install SSH on Windows") {
            steps {
                bat '''
                    powershell -Command "Set-ExecutionPolicy Unrestricted -Scope Process; \
                                        Install-PackageProvider -Name NuGet -MinimumVersion 2.8.5.201 -Force; \
                                        Install-Module -Name OpenSSHUtils -Force; \
                                        Install-WindowsFeature -Name OpenSSH-Server; \
                                        Start-Service sshd; \
                                        Set-Service -Name sshd -StartupType 'Automatic'; \
                                        if ((Get-WindowsCapability -Online | Where-Object Name -like 'OpenSSH.Client*').State -ne 'Installed') { \
                                            Add-WindowsCapability -Online -Name OpenSSH.Client~~~~0.0.1.0 \
                                        }"
                '''
            }
        }
        stage("Verify SSH connection to server") {
            steps {
                sshagent(credentials: ['aws']) {
                    bat '''
                        ssh -o "sag-aws-key.pem" ubuntu@ec2-13-211-134-87.ap-southeast-2.compute.amazonaws.com whoami
                    '''
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
}
