<?php
  header('Content-type: application/xml; charset=utf-8');
  echo '<?xml version="1.0" encoding="UTF-8"?>';

  require 'resurse.php';

  $dateString = date("Y-m-d");

  $xmlString = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
  <loc>http://saturn-games.com/</loc>
  <lastmod>'.$dateString.'</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
  </url>
  <url>
  <loc>http://saturn-games.com/contact.php</loc>
  <lastmod>'.$dateString.'</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
  </url>
  <url>
  <loc>http://saturn-games.com/index.php?popular_games=1</loc>
  <lastmod>'.$dateString.'</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
  </url>
  <url>
  <loc>http://saturn-games.com/mobile</loc>
  <lastmod>'.$dateString.'</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
  </url>';

  $selectGames = "select * from games;";
  $resGames = $conn->query($selectGames);

  while($row = $resGames->fetch_assoc()) {
    $xmlString = $xmlString.'
    <url>
    <loc>http://saturn-games.com/game/'.$row["permalink"].'</loc>
    <lastmod>'.$dateString.'</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    </url>
    ';
if($row['isMobile']==1){
    $xmlString = $xmlString.'
    <url> <loc>http://saturn-games.com/mobile/game/'.$row["permalink"].'</loc>
    <lastmod>'.$dateString.'</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    </url>
    ';
}
  }

  $selectPlayers = "select * from players;";
  $resPlayers = $conn->query($selectPlayers);
  while($row = $resPlayers->fetch_assoc()) {
    $xmlString = $xmlString.'
    <url>
    <loc>http://saturn-games.com/profile/'.$row["username"].'</loc>
    <lastmod>'.$dateString.'</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    </url>
    ';
  }
  $xmlString = $xmlString."</urlset>";

  echo $xmlString;

?>
