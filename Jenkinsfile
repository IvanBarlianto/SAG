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
                        bat 'for /F "tokens=*" %i IN (\'docker ps -aq\') DO docker rm -f %i'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
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
        stage("Deploy to Docker Swarm") {
            steps {
                script {
                    def swarmToken = "SWMTKN-1-2mz9k2pm63x1slzdq38dmq3y3yw95ld8k6l44ptlm79n9wqv30-32x985hdgboj5iazfbkvs3o7p"
                    def managerIP = "192.168.65.3"
                    def managerPort = "2377"

                    try {
                        // Leave the existing swarm (if any)
                        bat(script: 'docker swarm leave --force', returnStatus: true)
                        
                        // Initialize Swarm (if needed)
                        def initOutput = bat(script: 'docker swarm init', returnStdout: true, returnStatus: true)
                        echo initOutput

                        // Join the Swarm
                        bat "docker swarm join --token ${swarmToken} ${managerIP}:${managerPort}"
                    } catch (Exception e) {
                        echo "Failed to join Docker Swarm: ${e.message}"
                    }

                    // Deploy stack
                    bat 'docker stack deploy -c docker-compose.yml my_laravel_stack'
                }
            }
        }
    }
}
