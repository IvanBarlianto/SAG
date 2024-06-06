pipeline {
    agent any
    tools{
        jdk 'OpenJDK-17'
        maven 'maven3'
    }
    stages {
        stage("SCM") {
            steps {
                git branch: 'Ivan', changelog: false, credentialsId: 'github', poll: false, url: 'https://github.com/IvanBarlianto/SAG.git'
            }
        }
        stage("Compile") {
            steps {
                sh "mvn clean compile"
            }
        }
        stage("Sonarqube Analysis") {
            steps {
                echo 'Hello World'
            }
        }
        stage("Deploy to Tomcat") {
            steps {
                    echo 'Hello World'
                }
            }
        }
    }

