docker rmi $(docker images | grep "^<none>" | awk "{print $3}")

docker build -t c-build docker/build/c/
docker build -t cpp-build docker/build/cpp/
docker build -t go-build docker/build/go/
docker build -t py2-build docker/build/py2/
docker build -t php-build docker/build/php/
docker build -t java-build docker/build/java/

docker build -t judgy-run docker/run/
docker build -t judgy-compare docker/compare/
