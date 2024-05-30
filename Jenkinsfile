pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building...'
                // Contoh perintah build
                sh 'make build'
            }
        }

        stage('Test') {
            steps {
                echo 'Testing...'
                // Contoh perintah test
                sh 'make test'
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying...'
                // Contoh perintah deploy
                sh 'make deploy'
            }
        }
    }

    post {
        always {
            echo 'This will always run'
        }
        success {
            echo 'This will run only if successful'
        }
        failure {
            echo 'This will run only if failed'
        }
    }
}
