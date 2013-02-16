<h1>senior_project</h1>

<h2>Coding Guidelines</h2>

<p>
No php short tags.
Instead of <code> &#60;?= </code> we should use <code> &#60;?php echo </code>
<br/>
<a href="http://stackoverflow.com/questions/1386620/php-echo-vs-php-short-tags/">http://stackoverflow.com/questions/1386620/php-echo-vs-php-short-tags/</a>
<p/>


<h2>Source Control Guidelines</h2>

<p>Every commit should have a meaningful message.</p>
<p>Commits should be small.</p>

<p>
	It would be sane for each one of us to develop our stuff on a "development" separate branch and then merge it to the master once stable.
</p>

<p>
	The best way I found of doing this is first merging the destiny branch (master) into our development branch first.</p>
<p>Conflicts should be solved in the "development" branch.</p>
<p>After the "development" branch is conflict free, it should be merged back to the master branch with a nice commit message</p>



<h2>Code Deployment Guide</h2>

<p>
1- Connect to the remote server ussing ssh
</p>

<p>
2- Enter the website directory
  <p><code>
    cd /var/www/html/senior-projects/
  </code></p>
</p>  

<p>
3- Switch to the master branch. Or any branch you want to publish
  <p><code>
    sudo git checkout master  
  </code></p>
</p>

<p>
4- Download the latest version of the source code
  <p><code>
    sudo git pull
  </code></p>
</p>
