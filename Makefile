livedoc: var/cache
	sphinx-autobuild -b html doc/source var/cache/$@
var/cache:
	mkdir -p $@
	chmod 777 var/cache
clean:
	rm -rf var/cache
