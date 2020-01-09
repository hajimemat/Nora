let g:ale_php_phpcs_standard="./phpcs.xml"
let g:VimuxUseNearest=0

map <NORA>r :call VimuxRunCommand("./vendor/bin/phpunit --no-coverage  --color  --testdox ".bufname("%"))<CR>
map <SUBNORA>l :VimuxRunLastCommand<CR>
