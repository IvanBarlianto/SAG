pipeline {
    agent any

    environment {
        TF_VAR_aws_region = 'ap-southeast-2'
        TF_VAR_instance_ami = 'ami-080660c9757080771'
        TF_VAR_instance_type = 't2.micro'
        TF_VAR_key_name = 'sag-aws-key'
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
                        bat 'docker volume rm -f'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage("Verify SSH connection to server") {
            steps {
                sshagent(credentials: ['ssh-jenkins-plugin']) {
                    bat '''
                        ssh -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami
                    '''
                }
            }
        }

    stages {
        stage("Terraform Init") {
            steps {
                script {
                    sh 'terraform --version'
                    sh 'terraform init'
                }
            }
        }
        stage("Terraform Apply") {
            steps {
                script {
                    sh 'terraform apply -auto-approve'
                    sh 'terraform output -json > tf-output.json'
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
