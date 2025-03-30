build-phar:
	php --define phar.readonly=0 create-phar.php

clean:
	rm lib/*