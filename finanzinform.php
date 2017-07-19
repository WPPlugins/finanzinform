<?php
/*
Plugin Name: Finanzinform
Plugin URI: http://www.finanzinform.de/plugin.html
Description: The Finanzinform plugin includes a ticker from the most significant stock exchanges by http://www.finanzinform.de/
Version: 1.0
Author: Thomas Trimmel
Author URI: http://www.finanzinform.de/
License: GPL3
*/

function finanzinformnews()
{
  $options = get_option("widget_finanzinformnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Finanzinform News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.finanzinform.de/blog/rss.php'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_finanzinformnews($args)
{
  extract($args);
  
  $options = get_option("widget_finanzinformnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Finanzinform News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  finanzinformnews();
  echo $after_widget;
}

function finanzinformnews_control()
{
  $options = get_option("widget_finanzinformnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Finanzinform News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['finanzinformnews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['finanzinformnews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['finanzinformnews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['finanzinformnews-CharCount']);
    update_option("widget_finanzinformnews", $options);
  }
?> 
  <p>
    <label for="finanzinformnews-WidgetTitle">Widget Title: </label>
    <input type="text" id="finanzinformnews-WidgetTitle" name="finanzinformnews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="finanzinformnews-NewsCount">Max. News: </label>
    <input type="text" id="finanzinformnews-NewsCount" name="finanzinformnews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="finanzinformnews-CharCount">Max. Characters: </label>
    <input type="text" id="finanzinformnews-CharCount" name="finanzinformnews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="finanzinformnews-Submit"  name="finanzinformnews-Submit" value="1" />
  </p>
  
<?php
}

function finanzinformnews_init()
{
  register_sidebar_widget(__('Finanzinform News'), 'widget_finanzinformnews');    
  register_widget_control('Finanzinform News', 'finanzinformnews_control', 300, 200);
}
add_action("plugins_loaded", "finanzinformnews_init");
?>