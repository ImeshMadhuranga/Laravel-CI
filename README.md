
# Project Setup and Deployment Guide

This document provides a step-by-step guide to set up and deploy the project locally using Docker containers. The project involves a Laravel application with associated Docker images for development purposes.

## Prerequisites

Before proceeding with the setup, ensure the following prerequisites are met:

-   Docker is installed on your machine.
-   Command-line interface (CLI) access to run commands.
-   Basic familiarity with Docker and development workflows.

## Clone Project and Navigate to Root Folder

1.  Clone the project repository from the designated source.

`git clone git@github.com:Learnkad/eclasslms.git` 

2.  Navigate to the root folder of the cloned project.

`cd <project_folder>` 

## Configure Environment Variables

1.  In the "application" folder, locate the file named `Example.env`.
    
2.  Rename `Example.env` to `.env`.

3.  In the docker/dev and env folder, you will find a file named prefix with tmp. This file contains environment variables required for the Docker development setup.
    
4.  Remove the `tmp.` prefix from the filenames

    In dev folder
    
    `.env`
    
    In env folder
    
    `app.env`
    `adminer.env`
    `mysql.env`
    `proxy.env`

## Initialize Docker Containers

1.  Run the following command to start Docker containers and set up the development server:
`make up` 
 This command downloads the necessary Docker images and configures the development server.
    
3.  Wait until the process finishes. Upon successful completion, you'll see a message indicating that the local server is up and running.
    
 ✔ Network dev_default                                                                                                                                    Created    
 ✔ Container dev-proxy-1                                                                                                                                  Started     
 ✔ Container dev-mailhog-1                                                                                                                                Started      
 ✔ Container dev-app-1                                                                                                                                    Started      
 ✔ Container dev-static-1                                                                                                                                 Started      
 ✔ Container dev-adminer-1                                                                                                                                Started      
 ✔ Container dev-mysql-1                                                                                                                                  Started   

## Additional Setup within Docker Container

1.  After the Docker setup is complete, run the following command to access the Docker container shell:

    `make shell` 

3.  Inside the Docker container shell, run the following commands sequentially:
    
    a. Install Laravel composer packages:
    
    `composer install` 
    
    b. Install Node.js packages:
 

    `npm install` 
    

## Database Setup

1.  Access the database adminer by navigating to `localhost:8001` in your web browser.
    
2.  Import the database dump using the adminer interface.
    

## Accessing the Application

1.  Once the database is imported, access the application by navigating to `localhost:8000` in your web browser.
    
