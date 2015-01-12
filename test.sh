#docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/submissions/549f2c0af171f45b618b4567/54a72c02f171f4a0238b4569:/solution go-build
#docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/submissions/549f2c0af171f45b618b4567/54a72c02f171f4a0238b4569:/solution cpp-build
#docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/submissions/549f2c0af171f45b618b4567/54a72c02f171f4a0238b4569:/solution py2-build
docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/submissions/549f2c0af171f45b618b4567/54a72c02f171f4a0238b4569:/solution php-build

docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/54a72c02f171f4a0238b4569/in:/in judgy-run
docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/54a72c02f171f4a0238b4569/out:/correct_out judgy-compare


