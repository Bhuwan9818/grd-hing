<?php
/**
 * Header include: <head> + sticky navbar.
 * Uses relative image paths — keep this file included from files
 * that live at the project root (e.g. index.php).
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>G.R.D Hing — Bandhani Hing Churan | Pure, Plant-Based Asafoetida</title>
<meta name="description" content="G.R.D Hing brings authentic Bandhani hing churan to your kitchen — plant-based, additive-free, and ground for real Indian cooking.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Yeseva+One&family=Rozha+One&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="grain" aria-hidden="true"></div>

<header class="navbar">
  <div class="container">
    <a href="#top" class="brand">
      <img src="images/logo.png" alt="G.R.D Hing emblem — bullock cart mark">
      <div class="brand-text">
        <div class="brand-name">G.R.D Hing</div>
        <div class="brand-sub">Panditji Hing Kayam</div>
      </div>
    </a>

    <nav class="nav-links" aria-label="Primary">
      <a href="#top">Home</a>
      <a href="#shop">Shop</a>
      <a href="#benefits">Benefits</a>
      <a href="#kitchen">Recipes</a>
      <a href="#reviews">Reviews</a>
    </nav>

    <div class="nav-actions">
      <button class="icon-btn" aria-label="Search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>
      <button class="icon-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      </button>
      <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
