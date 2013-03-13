<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('all_skill_names'))
{
    function all_skill_names($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return ' "C#", "C# 2.0/3.5", "C# 3.0", "C# 2.0", "C# 4.0", "Visual C#", "F#", "GNU/Linux", "C", "C++", "Java", "JavaServlets", "JavaScript", "jQuery", ".NET", ".NET CLR", "SQL", "Oracle", "MySQL", "Visual Studio", "XMLHTTP", "XML", "XSLT", "XSL", "Linux Server", "AJAX", "REST", "Entity Framework", "ADO.NET", "ADO", "PHP", "PHP 4/5", "PHPNuke", "phpMyAdmin", "HTML", "HTML 5", "Ruby", "Python", "NoSQL", "NetBeans", "Eclipse", "iOS" ';
        }
        else
        {
            throw new Exception('not implemented');
        }
    }
}

?>