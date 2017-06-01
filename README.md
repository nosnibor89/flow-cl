# Shareable Innovations gateway to Flow.cl

## Local setup

1. Install [Virtualbox](https://www.virtualbox.org/wiki/Downloads)
2. Install the [Docker Toolbox](https://www.docker.com/products/docker-toolbox)
3. Create a new machine, let's name it **shareableinnovations** 
 ```
 docker-machine create -d virtualbox --virtualbox-memory 2048 shareableinnovations
 ```
2. Connect your current *shell session* to this new machine, you'll need to do this for every session
 ```
 eval $(docker-machine env shareableinnovations)
 ```
3. Let's ensure that's run every session
 > If you use bash (default) you can do this.
 > ```
 > echo '$(eval docker-machine env shareableinnovations)' >> ~/.bashrc
 > ```
 > If you use ZSH (power users!) you can do this
 > ```
 > echo 'eval $(docker-machine env shareableinnovations)' >> ~/.zshrc
 > ```
4. Run this command to set up the containers:
 ```
 docker/setup.sh
 ```
5. If you haven't already, make sure to add the virtualhost to yous `/etc/hosts` file
 ```
 echo "$(docker-machine ip shareableinnovations) payment-flow.dev" | sudo tee -a /etc/hosts
 ```
That's it! Now go build something cool.
