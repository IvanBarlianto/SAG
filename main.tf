terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "aws" {
  region = "ap-southeast-2"
  access_key = "AKIAXYKJSPPBUQNLJ2X2"
  secret_key = "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"
}

variable "key_name" {
  description = "Name of the key pair"
  default     = "sag-aws-key"  
}

resource "aws_security_group" "allow_http_ssh" {
  description = "Allow HTTP and SSH inbound traffic"

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 3000
    to_port     = 3000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port       = 0
    to_port         = 0
    protocol        = "-1"
    cidr_blocks     = ["0.0.0.0/0"]
  }
}

resource "aws_instance" "public_instance" {
  ami                    = "ami-080660c9757080771"
  instance_type          = "t2.micro"
  vpc_security_group_ids = [aws_security_group.allow_http_ssh.id]

  tags = {
    Name = "instance_terraform_2"
  }
}

output "instance_ip" {
  value = aws_instance.public_instance.public_ip
}
