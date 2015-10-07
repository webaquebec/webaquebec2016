# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"
  config.vm.provider "virtualbox"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.synced_folder "../public", "/www/sites/waq2016/public"
  config.vm.provision "shell", path: "script/install.sh"

end
