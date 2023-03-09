{ pkgs }: {
	deps = {
    pkgs.sqlite.bin
		pkgs.php80Packages.composer
  pkgs.postgresql
  pkgs.php74
	];
}