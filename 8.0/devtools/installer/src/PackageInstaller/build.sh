#!/bin/sh

mkdir -p /home/abuild/packages/php74
ln -s /php74/packages /home/abuild/packages/php74/x86_64

ls -alR /home/abuild
ls -alR /php74

build_package() {
  package=$1

  cd $package || return

  abuild -f checksum
  abuild -rf
  if [ $? -eq 0 ]; then
    echo "Successfully built $package"
  else
    echo "Failure building $package" >&2
    exit 1
  fi

  cd ..
}

for package in php7*
do
  build_package $package
done

ls -alR /home/abuild
ls -alR /php74
