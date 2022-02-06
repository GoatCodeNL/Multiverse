# WUBBA LUBBA DUB-DUB!
This was made as a response to the [Bax Music Rick and Morty developer case](https://github.com/Baxshopnl/Rick-and-Morty-Case) 

## Disclaimer
If you are applying for a job at Bax Music and you try to use my code to cheat your way through the challenge... DON'T!

It is filled with bugs, the code is terrible, it looks even worse. Using this as an example or a cheat will 
most likely end your development career. Just cloning the code can melt your harddrives and overload the USB-bridge.

@Bax employees: Please do not read this disclaimer.

## Running this thing
Requirements:
- computer
- docker
- docker-compose

No need to do additional setup with composer or yarn to just run.

```bash
docker-compose up -d
```
Then enter the multiverse at http://0.0.0.0:8137

## Debug and development

#### Requirements
- node 12
- yarn
- composer

To enable debug mode and dev volume bindings simply uncomment the lines from docker-compose.yaml
```yaml
#    volumes:
#      - .:/var/www    # for dev purposes
```


Build & restart the application
```bash
composer install
yarn install
yarn run dev
docker-compose up -d
```

# Known issues and shortcomings
- ~~Unit tests (really should have done those, but I ran out of time with work and 2 kids running around at home due to covid)~~ Added after original submission. I just could not leave this here untested. Quick and dirty but good enough tests.
- .env.prod (just added for quick demonstration purpose. Should of course never be included in the versioning)
- Templates could really use some includes as this is lots of code duplication. 
- Proper error handling in the client. Now it leaks HttpClient exceptions and general exceptions which is not cool
