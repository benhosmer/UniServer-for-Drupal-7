#!C:\mpg_uniserver_test_well_17\usr\bin\perl

sub urldecode{ 
	local($val)=@_; 
	$val=~s/\+/ /g; 
	$val=~s/%([0-9A-H]{2})/pack('C',hex($1))/ge; 
	return $val;
}

print "Content-type: text/html\n\n";
print "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
<title>The Uniform Server</title>
<meta name=\"author\" content=\"Olajide Olaolorun\" />
<meta http-equiv=\"page-enter\" content=\"blendtrans(duration=0.1)\" />
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
<link rel=\"icon\" href=\"../images/favicon.ico\" />
	  <link title=\"Homepage\" href=\"../index.php\" rel=\"top\" />
	  <link title=\"Up\" href=\"../index.php\" rel=\"up\" />
 	  <link title=\"First page\" href=\"../index.php\" rel=\"first\" />
	  <link title=\"Previous page\" href\"../index.php\" rel=\"previous\" />
	  <link title=\"Next page\" href=\"../index.php\" rel=\"next\" />
	  <link title=\"Last page\" href=\"../index.php\" rel=\"last\" />
	  <link title=\"Table of contents\" href=\"../index.php\" rel=\"toc\" />
	  <link title=\"Site map\" href=\"../index.php\" rel=\"index\" />
</head>

<body>";


print "
<div id=\"main\">
<h2>&#187; CGI Enviroment</h2>
<h3>Displaying CGI Environment</h3>"; 

foreach $env_var (keys %ENV){ 
	print "<p><b>$env_var</b> = <i>$ENV{$env_var}</i> <br /></p>\n"; 
} 

print "</div>";

print"
<p class=\"copyright\">
<span class=\"name\">Admin Panel 2.1</span> | © 2009 The Uniform Server Development Team
</p>

</body>
</html>";
