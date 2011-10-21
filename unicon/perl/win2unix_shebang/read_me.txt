 Perl scripts developed on Windows will not run on a Unix machine
 they require conversion to a Unix format.  

 This script copies all files in cgi-bin to a new folder \cgi-bin-unix\ 

 Scripts in this new folder are converted from Windows to Unix format. 

 a) Converts end of line:  Dec(#10#13=>#13) Hex 0D0A to 0A
 b) Replaces Windows shebang with Unix shebang

