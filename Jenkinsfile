pipeline {
    agent any

    tools {
        jdk 'OpenJDK-17' // Assuming the name you gave when configuring the JDK
        maven 'maven3'
    }

    environment {
        SCANNER_HOME = 'C:/ProgramData/Jenkins/.jenkins/tools/hudson.plugins.sonar.SonarRunnerInstallation/sonarqube-scanner'
    }

    stages {
        stage("SCM") {
            steps {
                echo "Cloning repository..."
                git branch: 'Ivan', changelog: false, credentialsId: 'github', poll: false, url: 'https://github.com/IvanBarlianto/SAG'
                echo "Repository cloned. Listing files:"
                bat 'dir'
            }
        }
        stage("Compile") {
            steps {
                echo "Compiling the project..."
                bat 'dir' // Menampilkan isi direktori sebelum menjalankan Maven
                bat "mvn clean compile"
            }
        }
        stage("Sonarqube Analysis") {
            steps {
                withSonarQubeEnv('SonarQube') {
                    bat "${SCANNER_HOME}/bin/sonar-scanner"
                }
            }
        }
        stage("Deploy to Tomcat") {
            steps {
                echo 'Hello World'
            }
        }
    }
}
