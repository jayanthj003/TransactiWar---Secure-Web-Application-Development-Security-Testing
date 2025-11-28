# TransactiWar---Secure-Web-Application-Development-Security-Testing: Network Security Project

### Mock Transaction website
- The project showcases how to  prevent various cyber attacks that we learned through the course [CS6903 Network Security](https://cse.iith.ac.in)
- Prevent various cyber attacks without using additional packages in PHP

### Running server 
#### Prerequsite
1. Docker cli

#### Change the server port
- Currently the website will be accessible via port 10001 (mentioned in the .env file)
- To change the website port, edit the `SERVER_PORT` variable in the .env file

#### Container names used
- Since container names should be uniqe on the same machine, the container names are prefiexed with 'team-6-'
- Container names used
    - team-6-httpd-server
    - team-6-php-fpm
    - team-6-source-code
    - team-6-mysql

#### Initializing MySQL
- No additional steps required(The project contains a SQL data dump that docker uses to initialize the database)

#### How to run the server
- At the root of the project directory, run the following command in the terminal
```sh
docker compose build
docker compose up --wait 
```
- Make sure all 4 containers are running
```sh
docker ps -f 'name=team-6'
```
- Visit "https://localhost:10001/login" in browser

#### Stopping all containers
- At the root of the project directory, run the following command to stop all containers
```sh
docker compose down
```

### Contributions
1. Divyanshu Ranjan - CS24MTECH11013
- Frontend Code 
2. Jayanth Jatavath - CS24MTECH11014
- Frontend Wireframe Design
3. Peddi Manognya - CS24MTECH12020
- Frontend Code 
4. Krishna Teja B - CS24MTECH12011
- Docker setup
- Backend code(php, mysql)
5. Sai Sravan V - CS24MTECH02007
- Input validation(how to prevent attacks)
- Testing attacks/website

### References
1. [MySQL Transactions](https://dev.mysql.com/doc/refman/8.4/en/commit.html)
2. [HTTP header based authentication](https://security.stackexchange.com/questions/91546/any-reasons-for-using-basic-http-authentication)
3. [MySQL UUID support](https://dev.mysql.com/blog-archive/mysql-8-0-uuid-support/) 
4. [MySQL handling UUID](https://dev.mysql.com/blog-archive/storing-uuid-values-in-mysql-tables/)
5. [Apache TLS Configuration](https://httpd.apache.org/docs/2.4/ssl/ssl_faq.html#gid)
6. [Sanitizing Images](https://www.reddit.com/r/web_design/comments/3zsrqr/sanitizing_uploaded_images_through_php/)
7. [PHP payloads in PNG](https://www.synacktiv.com/publications/persistent-php-payloads-in-pngs-how-to-inject-php-code-in-an-image-and-keep-it-there.html)
8. [Uploading files in PHP](https://stackoverflow.com/questions/33898834/uploading-a-profile-picture-and-displaying-it)
9. [HTML Templates in PHP](https://stackoverflow.com/questions/13071784/html-templates-php)
10. [Docker compose example config](https://github.com/Fresh-Advance/development/blob/master/services/mysql.yml)
11. [Integrate Apache and PHP-FPM](https://www.reddit.com/r/PHPhelp/comments/yvoo9r/apachephp_and_phpfpm_in_separate_docker/)
12. [PHP router](https://www.freecodecamp.org/news/how-to-build-a-routing-system-in-php/)
13. [PHP and PHP-FPM setup](https://www.pascallandau.com/blog/php-php-fpm-and-nginx-on-docker-in-windows-10/) 
14. [Variables in Docker compose](https://serversforhackers.com/c/div-variables-in-docker-compose)
15. [PHP and PHP-FPM setup 1](https://stackoverflow.com/questions/29905953/how-to-correctly-link-php-fpm-and-nginx-docker-containers)
16. [Youtube tutorial on PHP 1](https://www.youtube.com/watch?v=BSvzZvw_T64&list=PLQH1-k79HB396mS8xRQ5gih5iqkQw-4aV)
17. [Youtube tutorial on PHP 2](https://www.youtube.com/watch?v=yy8QogjpuiI&list=PLIorEuqMFFjMOoduM9Ijk7Y7Oz88lG8Q1&index=19)
18. [Apache Redirect All requests to index.php](https://www.slashnode.com/articles/devops/2013-12-24-redirect-all-requests-to-index-php)
19. [Using exit after redirect](https://thedailywtf.com/articles/WellIntentioned-Destruction)
20. [Docker compose docs](https://docs.docker.com/reference/cli/docker/compose/)
21. [PHP File upload guide](https://inspector.dev/ultimate-guide-to-php-file-upload-security/)
22. [PHP Send file](https://stackoverflow.com/questions/2882472/send-file-to-the-user)
23. [PHP Session settings](https://www.php.net/manual/en/session.security.ini.php)
24. [PHP official Documentation](https://www.php.net/manual/en/index.php)
25. [PHP mysqli Documentation](https://www.php.net/manual/en/intro.mysqli.php)
