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
                git branch: 'Ivan', changelog: false, credentialsId: 'github', poll: false, url: 'https://github.com/IvanBarlianto/SAG'
            }
        }
        stage("Compile") {
            steps {
                sh "mvn clean compile"
            }
        }
        stage("Sonarqube Analysis") {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh "${SCANNER_HOME}/bin/sonar-scanner"
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
