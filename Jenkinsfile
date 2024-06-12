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
                        bat 'docker rm -f $(docker ps -a -q)'
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
                    def swarmToken = "SWMTKN-1-686ylfwd82sgyib3qy0tsj69woj7ri3p6qifkwgfg42rog5zvc-2y8yz1cvmrdg15nbndscjvpx5"
                    def managerIP = "192.168.65.3"
                    def managerPort = "2377"
                    try {
                        bat 'docker swarm leave --force'
                        bat 'docker swarm join --token SWMTKN-1-686ylfwd82sgyib3qy0tsj69woj7ri3p6qifkwgfg42rog5zvc-2y8yz1cvmrdg15nbndscjvpx5 192.168.65.:2377'
                    } catch (Exception e) {
                        echo "Failed to join Docker Swarm: ${e.message}"
                    }
                    bat 'docker stack deploy -c docker-compose.yml my_laravel_stack'
                }
            }
        }
    }
}
