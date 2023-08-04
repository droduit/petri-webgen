<?php 
include_once('header.inc.php');
$debug_mode = false;
$mdpRequired = true;

if(isset($_POST['newUpload']) || $debug_mode) {
    unset($_SESSION['file']);
    unset($_SESSION['pwd']);
    unset($_SESSION['stamp']);
}

if(count($_POST) == 0) {?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Petri WebGen - Convert Petri Nets to Web experiences</title>
		<meta charset="UTF-8">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/common.js" mdpRequired="<?= $mdpRequired ? 1 : 0 ?>" debug_mode="<?= $debug_mode ? 1 : 0 ?>"></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="icon" href="img/favicon.svg">
	</head>
	
	<body>

		<div style="margin: auto; margin-top: 70px; width: 400px">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 691.62 100">
  <g fill="#404040">
    <path d="M138.79 89.01c-.53 0-1.19.13-1.58.4a2.82 2.82 0 0 0-1.19 2.37v5.26c0 .27 0 .66.13.93.13.26.13.52.4.78a2.8 2.8 0 0 0 2.24 1.06h7.1v-1.98h-7.1c-.27 0-.53 0-.66-.13s-.13-.4-.13-.66v-5.26c0-.27 0-.53.13-.66.26-.13.4-.13.66-.13h7.1V89z"/>
    <path d="M157.96 90.8c.27 0 .52.1.7.19.67.26 1.2.79 1.33 1.31.13.4.26.66.26 1.06v2.1c0 .4-.13.8-.26 1.06-.14.66-.66 1.05-1.32 1.31-.26.14-.66.14-1.05.14h-2.64c-.4 0-.66 0-1.05-.14a2.57 2.57 0 0 1-1.32-1.31c-.13-.27-.13-.66-.13-1.06v-2.1c0-.4 0-.66.13-1.06.26-.65.66-1.05 1.32-1.31.4-.13.66-.13 1.05-.13h2.64c.11-.04.23-.06.34-.06zm-.03-1.93-.31.01h-2.86a4.3 4.3 0 0 0-4.26 4.48v2.1c0 .66.14 1.2.27 1.85a5.17 5.17 0 0 0 2.37 2.37c.66.26 1.18.26 1.84.26h2.64l.3.01a4.16 4.16 0 0 0 3.91-2.64c.26-.66.4-1.19.4-1.85v-2.1c0-.66-.14-1.32-.4-1.85a4.16 4.16 0 0 0-3.9-2.64z"/>
    <path d="M168.38 88.82c-.13 0-.27.06-.36.06-.27.13-.4.26-.53.4 0 .13-.13.39-.13.52v10h1.98v-7.63l7.24 7.5c0 .14.13.14.26.27h.4c.26 0 .52 0 .65-.26.27-.14.27-.4.27-.66v-10h-1.98v7.63l-7.1-7.5a.99.99 0 0 0-.54-.27.23.23 0 0 0-.16-.06z"/>
    <path d="m182.5 89.01 5.14 10.4c.13.4.52.53.92.53s.66-.13.92-.53l5.14-10.4h-2.24l-3.82 7.77-3.82-7.77z"/>
    <path d="M201.6 93.49v1.97h6.19V93.5zm-2.11-4.48c-.4.27-.66.53-.66.92v7.11c0 .53.13.93.26 1.32.4.66.93 1.05 1.58 1.32.4.13.66.13.93.13h7.1v-1.98h-7.1c-.27 0-.4 0-.53-.13a1 1 0 0 1-.26-.66V91h7.9v-2z"/>
    <path d="m222.54 89-.27.01h-7.77c-.4.27-.66.53-.66.92v9.88h1.98v-8.82h6.71c.14 0 .14.13.14.13v2.24c-.14.13-.27.26-.4.52-.13.14-.4.14-.53.27-.26.13-.4.13-.65.13h-4.48v1.97h1.44l4.09 3.56h3.03l-4.08-3.56c.79 0 1.7-.26 2.36-.79.53-.39.93-1.05 1.06-1.58.13-.39.13-.79.13-1.18v-1.32c0-.26 0-.52-.13-.79a2.03 2.03 0 0 0-1.97-1.6z"/>
    <path d="M228.98 89.01V91h4.35v8.82h1.98V91h4.34v-2z"/>
    <path d="M244.26 89.01v10.8h1.98V89z"/>
    <path d="M252.78 88.82c-.12 0-.27.06-.36.06-.13.13-.4.26-.4.4a1 1 0 0 0-.26.52v10h1.98v-7.63l7.24 7.5c0 .14.13.14.27.27h.39c.26 0 .53 0 .66-.26.26-.14.4-.4.4-.66v-10h-1.98v7.63l-7.24-7.5c-.14-.14-.27-.27-.53-.27a.23.23 0 0 0-.17-.06z"/>
    <path d="M270.72 89.01c-.52 0-1.05.13-1.57.4a3.2 3.2 0 0 0-1.19 2.37v5.26c0 .27.13.66.13.93l.4.78a3 3 0 0 0 2.23 1.06h7.12c.26 0 .52-.13.79-.27a1 1 0 0 0 .26-.65V94.4a1 1 0 0 0-.26-.66c-.2-.19-.39-.31-.58-.31a.45.45 0 0 0-.21.05h-6.2v1.97h5.28v2.37h-6.2c-.26 0-.39 0-.52-.13s-.27-.4-.27-.66v-5.26a1 1 0 0 1 .27-.66c.13-.13.4-.13.52-.13h8.04V89z"/>
    <path d="M299.97 88.98c-.14 0-.27.01-.4.03h-4.48c-.27 0-.53 0-.8.13-1.18.27-1.97 1.45-1.97 2.64v8.03h1.98v-8.03c0-.27.13-.53.26-.66s.26-.13.53-.13h4.47c.27 0 .53 0 .66.13.4.13.66.4.92.79 0 .26.14.53.14.66v.92c0 .26 0 .53-.14.66-.13.26-.26.4-.39.52-.13.14-.26.27-.53.4-.13.13-.4.13-.66.13h-4.47v1.98h4.47a3.63 3.63 0 0 0 3.56-2.5c0-.27.13-.66.13-1.19v-.79c0-.92-.4-1.71-.79-2.37a3.55 3.55 0 0 0-.79-.66 2.42 2.42 0 0 0-1.7-.7z"/>
    <path d="M310.89 93.49v1.97h6.19V93.5zm-2.24-4.48c-.4.27-.53.53-.53.92v7.11c0 .53 0 .93.26 1.32.27.66.93 1.05 1.59 1.32.26.13.66.13.92.13h7.1v-1.98h-7.1c-.26 0-.53 0-.66-.13s-.13-.4-.13-.66V91h7.9v-2z"/>
    <path d="M322.21 89.01V91h4.35v8.82h1.97V91h4.35v-2z"/>
    <path d="m346.28 89-.24.01h-7.9c-.4.27-.65.53-.65.92v9.88h1.97v-8.82h6.85v.13c.13.13.13.13.13.26v1.32c0 .13-.13.4-.13.66-.13.13-.27.26-.4.52-.13.14-.26.14-.52.27-.14.13-.4.13-.66.13h-4.48v1.97h1.45l4.08 3.56h3.03l-4.08-3.56c.92 0 1.71-.26 2.37-.79a3.6 3.6 0 0 0 1.19-1.58c0-.39.13-.79.13-1.18v-1.32c0-.26-.13-.52-.13-.79a2.13 2.13 0 0 0-2-1.6z"/>
    <path d="M353.55 89.01v10.8h1.98V89z"/>
    <path d="M370.37 88.82c-.13 0-.27.06-.36.06-.26.13-.4.26-.53.4-.13.13-.13.39-.13.52v10h1.98v-7.63l7.11 7.5c.13.14.26.14.4.27h.4c.25 0 .51 0 .65-.26.26-.14.26-.4.26-.66v-10h-1.97v7.63l-7.12-7.5a.97.97 0 0 0-.52-.27.23.23 0 0 0-.17-.06z"/>
    <path d="M388.45 93.49v1.97h6.18V93.5zm-2.24-4.48c-.26.27-.53.53-.53.92v7.11c0 .53 0 .93.27 1.32.26.66.92 1.05 1.58 1.32.39.13.65.13.92.13h7.1v-1.98h-7.1c-.27 0-.53 0-.66-.13s-.13-.4-.13-.66V91h7.9v-2z"/>
    <path d="M399.77 89.01V91h4.35v8.82h1.97V91h4.35v-2z"/>
    <path d="M417.81 89.01c-.26 0-.66 0-.92.13a3.25 3.25 0 0 0-2.24 3.03c0 .92.4 1.85 1.19 2.5.52.4 1.31.66 1.97.8h4.6a.18.18 0 0 1 .15-.07c.1 0 .21.06.39.06.26.14.52.4.52.66.14.13.14.4.14.53 0 .26-.14.66-.27.92-.26.13-.66.26-.92.26h-7.64v1.98h7.64a3.6 3.6 0 0 0 1.98-.66c.26-.26.52-.4.65-.66.27-.52.53-1.18.53-1.84 0-.4-.13-.79-.13-1.05a3.7 3.7 0 0 0-1.05-1.45 4.51 4.51 0 0 0-1.98-.8h-4.6c-.27 0-.67 0-.8-.26a1.2 1.2 0 0 1-.4-.92c0-.26.14-.66.4-.92.13-.26.53-.26.8-.26h6.7V89z"/>
    <path d="M437.7 89.01V91h4.34v8.82h1.97V91h4.35v-2z"/>
    <path d="M460.03 90.8c.27 0 .52.1.7.19.67.26 1.06.66 1.32 1.31.14.4.27.66.27 1.06v2.1c0 .4-.13.8-.27 1.06a2.16 2.16 0 0 1-1.31 1.31c-.26.14-.66.14-1.05.14h-2.64c-.4 0-.79 0-1.05-.14a2.17 2.17 0 0 1-1.32-1.31c-.13-.27-.13-.66-.13-1.06v-2.1c0-.4 0-.66.13-1.06.13-.26.26-.52.53-.79.26-.26.52-.4.79-.52.26-.13.65-.13 1.05-.13h2.64c.11-.04.23-.06.34-.06zm-.04-1.93-.3.01h-2.86c-2.41 0-4.51 2.05-4.26 4.48v2.1a3 3 0 0 0 .27 1.85 4.62 4.62 0 0 0 2.37 2.37c.52.26 1.18.26 1.84.26h2.64l.3.01a4.15 4.15 0 0 0 3.9-2.64c.27-.66.4-1.19.4-1.85v-2.1c0-.66-.13-1.32-.4-1.85a4.15 4.15 0 0 0-3.9-2.64z"/>
    <path d="m477.07 89.01 1.84 10.14c0 .26.13.4.26.66.23.08.46.15.69.15a.9.9 0 0 0 .5-.15c.13 0 .26-.13.26-.27l3.69-5.92 3.55 5.92.4.4a1.25 1.25 0 0 0 1.18-.26c.13-.14.27-.27.27-.53l1.84-10.14h-2.1l-1.2 7.11-3.15-4.87c-.14-.13-.14-.26-.4-.4-.13-.06-.3-.1-.46-.1-.16 0-.33.04-.46.1l-.4.4-3.02 4.87-1.32-7.1z"/>
    <path d="M499.19 93.49v1.97h6.19V93.5zm-2.24-4.48c-.4.27-.53.53-.53.92v7.11c0 .53 0 .93.26 1.32.27.66.93 1.05 1.59 1.32.26.13.65.13.92.13h7.1v-1.98h-7.1c-.27 0-.53 0-.66-.13s-.13-.4-.13-.66V91h7.9v-2z"/>
    <path d="M518.94 90.99c.26 0 .4.13.4.4v.39c0 .26 0 .4-.14.66-.13.39-.4.65-.79.92h-4.34v1.97h4.47c.27 0 .53.13.66.13.4.14.8.53.92.93 0 .13.13.4.13.65v.4c0 .13 0 .13-.13.26v.13h-6.84V91zM511.96 89c-.4.27-.66.53-.66.92v8.96c0 .26.13.52.27.65.26.14.52.27.79.27h7.5c.4 0 .8 0 1.05-.27.53-.26.92-.65 1.19-1.31 0-.26.13-.53.13-.79v-.4a3 3 0 0 0-.53-1.84 2.58 2.58 0 0 0-1.18-1.18c.52-.66.79-1.45.79-2.24v-.4c0-.79-.27-1.45-.8-1.84a2.52 2.52 0 0 0-1.57-.53z"/>
    <path d="M538.16 93.49v1.97h6.2V93.5zm-2.24-4.48c-.4.27-.52.53-.52.92v7.11c0 .53 0 .93.26 1.32.26.66.92 1.05 1.58 1.32.27.13.66.13.92.13h6.98v-1.98h-6.98c-.26 0-.52 0-.65-.13-.14-.13-.14-.4-.14-.66V91h7.77v-2z"/>
    <path d="m549.36 89.01 4.6 5.4-4.6 5.4h2.63l3.29-3.82 3.3 3.82h2.63l-4.61-5.4 4.74-5.4h-2.77l-3.29 3.82-3.3-3.82z"/>
    <path d="m573.32 88.98-.4.03h-4.47c-.26 0-.53 0-.92.13-1.06.27-1.85 1.45-1.85 2.64v8.03h1.98v-8.03c0-.27.13-.53.26-.66s.27-.13.53-.13h4.47c.27 0 .4 0 .66.13.4.13.66.4.8.79.13.26.13.53.26.66v.92c0 .26-.13.53-.13.66-.13.26-.27.4-.4.52-.13.14-.26.27-.53.4-.13.13-.4.13-.66.13h-4.47v1.98h4.47c.93 0 1.72-.27 2.38-.8.52-.39.92-1.05 1.05-1.7.13-.27.26-.66.26-1.19v-.79c0-.92-.4-1.71-.92-2.37-.13-.26-.4-.53-.66-.66a2.71 2.71 0 0 0-1.7-.7z"/>
    <path d="M584.25 93.49v1.97h6.19V93.5zm-2.24-4.48c-.4.27-.66.53-.53.92v7.11c-.13.53 0 .93.27 1.32.26.66.79 1.05 1.58 1.32.26.13.52.13.92.13h6.98v-1.98h-6.98c-.26 0-.53 0-.66-.13s-.26-.4-.26-.66V91h7.9v-2z"/>
    <path d="m605.16 89-.24.01h-7.9c-.4.27-.66.53-.66.92v9.88h1.98v-8.82h6.72c.13 0 .13.13.13.13.13.13.13.13.13.26v1.32c0 .13-.13.4-.13.66-.13.13-.27.26-.4.52-.13.14-.26.14-.52.27-.14.13-.4.13-.66.13h-4.48v1.97h1.45l4.08 3.56h3.03l-4.08-3.56c.92 0 1.71-.26 2.37-.79.52-.39.92-1.05 1.05-1.58.13-.39.26-.79.26-1.18v-1.32c0-.26-.13-.52-.13-.79-.36-.96-1.16-1.6-2-1.6z"/>
    <path d="M612.43 89.01v10.8h1.98V89z"/>
    <path d="M622.7 93.49v1.97h6.19V93.5zm-2.24-4.48c-.26.27-.53.53-.53.92v7.11c0 .53 0 .93.27 1.32.26.66.92 1.05 1.58 1.32.26.13.66.13.92.13h7.11v-1.98h-7.1c-.27 0-.54 0-.67-.13s-.13-.4-.13-.66V91h7.9v-2z"/>
    <path d="M635.9 88.82c-.14 0-.25.06-.43.06l-.4.4a1 1 0 0 0-.26.52v10h1.98v-7.63l7.24 7.5c.13.14.27.14.4.27h.26c.26 0 .53 0 .8-.26.12-.14.25-.4.25-.66v-10h-1.97v7.63l-7.25-7.5c-.13-.14-.26-.27-.39-.27a.5.5 0 0 0-.23-.06z"/>
    <path d="M653.91 89.01c-.66 0-1.19.13-1.71.4a3.1 3.1 0 0 0-1.06 2.37v5.26c0 .4 0 .66.14.93 0 .26.13.52.26.78a3.08 3.08 0 0 0 2.37 1.06h7.1v-1.98h-7.1c-.26 0-.53 0-.66-.13s-.13-.4-.13-.66v-5.26c0-.27 0-.53.13-.66s.4-.13.66-.13h7.1V89z"/>
    <path d="M668.79 93.49v1.97h6.18V93.5zm-2.24-4.48c-.26.27-.53.53-.53.92v7.11c0 .53 0 .93.27 1.32.26.66.92 1.05 1.58 1.32.26.13.65.13.92.13h7.1v-1.98h-7.1c-.27 0-.53 0-.66-.13s-.13-.4-.13-.66V91h7.9v-2z"/>
    <path d="M683.67 89.01c-.27 0-.66 0-1.06.13-1.31.4-2.1 1.72-2.1 3.03 0 .92.4 1.85 1.18 2.5.53.4 1.19.66 1.98.8h4.6a.18.18 0 0 1 .14-.07c.08 0 .17.06.26.06.27.14.53.4.66.66 0 .13.13.4.13.53 0 .4-.13.66-.4.92-.26.13-.52.26-.91.26h-7.51v1.98h7.64c.66 0 1.45-.27 1.97-.66.27-.26.4-.4.53-.66.4-.52.66-1.18.66-1.84 0-.4-.13-.79-.27-1.05a2.5 2.5 0 0 0-.92-1.45 5.25 5.25 0 0 0-1.97-.8h-4.61c-.4 0-.66 0-.8-.26a1.2 1.2 0 0 1-.39-.92c0-.26.13-.66.4-.92.13-.26.39-.26.79-.26h6.71V89z"/>
    <path d="M136.02 76.1v1.59h555.42V76.1z"/>
    <path d="M149.19 3.69c-1.45 0-2.9.26-4.35.79a12.34 12.34 0 0 0-8.82 12.37v38.58h9.35V16.85c0-1.18.26-2.1 1.05-2.76.53-.66 1.58-1.05 2.77-1.05h21.2c1.05 0 2.1.26 3.16.65a6.95 6.95 0 0 1 3.95 4.22c.4.92.66 2.1.79 3.16v4.2c0 1.33-.13 2.38-.53 3.3a6.1 6.1 0 0 1-1.71 2.37 5.64 5.64 0 0 1-2.5 1.71c-.92.4-1.98.66-3.16.66h-21.2v9.35h21.2c1.05 0 2.37-.13 3.69-.4a17.73 17.73 0 0 0 7.5-3.42 15.81 15.81 0 0 0 5.27-7.77c.52-1.7.79-3.55.79-5.8v-4.2c0-1.19-.13-2.37-.4-3.69a17.02 17.02 0 0 0-3.42-7.5 15.82 15.82 0 0 0-7.77-5.4 18.96 18.96 0 0 0-5.66-.8z"/>
    <path d="M206.09 16.44c-1.87 0-3.63.5-5.28 1.33a12.16 12.16 0 0 0-6.33 7.38 16.5 16.5 0 0 0-.65 4.21v13.17c0 2.37.4 4.34 1.32 5.92a12.5 12.5 0 0 0 7.37 6.33c1.31.39 2.76.65 4.21.65h16.86v-8.95h-16.73a4.6 4.6 0 0 1-2.9-1.05 3.68 3.68 0 0 1-1.18-2.9V29.36a3.5 3.5 0 0 1 1.05-2.9 3.88 3.88 0 0 1 2.9-1.18h16.86c1.05 0 2.1.4 2.9 1.05a3.87 3.87 0 0 1 1.18 2.9c0 1.19-.4 2.1-1.06 2.9-.65.79-1.7 1.18-3.02 1.18h-16.86v8.96h16.86c2.37 0 4.47-.4 6.05-1.32a12.25 12.25 0 0 0 6.2-7.38c.52-1.44.65-2.76.65-4.2h.13c0-2.38-.52-4.36-1.31-6.07a12.56 12.56 0 0 0-7.5-6.18c-1.46-.4-2.9-.66-4.22-.66h-16.86c-.21-.02-.43-.02-.64-.02z"/>
    <path d="M252.42 3.69v12.77h-12.1v9.35h12.1v29.62h9.48V25.81h16.33v-9.35H261.9V3.69z"/>
    <path d="M297.2 16.46a13.69 13.69 0 0 0-8.56 2.9 11.6 11.6 0 0 0-3.95 5.92c-.4 1.19-.66 2.63-.66 4.35v25.8h9.34v-25.8c0-1.32.4-2.24 1.06-2.9.52-.53 1.58-.92 2.9-.92h21.06v-9.35z"/>
    <path d="M324.58 0v8.42h9.48V0zm0 16.46v38.97h9.48V16.46z"/>
    <path d="m365.14 3.69 8.69 48.45a5.04 5.04 0 0 0 1.71 2.9c.82.67 1.84 1 2.88 1 .81 0 1.64-.2 2.39-.6.66-.4 1.18-.93 1.58-1.59l17.25-28.17 17.12 28.17a4.77 4.77 0 0 0 3.94 2.25c1.23 0 2.48-.5 3.43-1.46a5.7 5.7 0 0 0 1.32-2.5L434 3.7h-9.48l-6.2 34.1-14.74-23.57a4.8 4.8 0 0 0-4-2.17c-.77 0-1.53.2-2.19.59-.79.4-1.32.92-1.71 1.58L380.93 37.8l-6.32-34.1z"/>
    <path d="M451.44 16.44c-1.8 0-3.66.5-5.32 1.33a11.57 11.57 0 0 0-6.19 7.38c-.52 1.45-.79 2.9-.79 4.21v13.17c0 2.37.53 4.34 1.32 5.92a12.76 12.76 0 0 0 7.5 6.33c1.32.39 2.77.65 4.09.65h16.98v-8.95h-16.85a4.4 4.4 0 0 1-2.9-1.05 3.68 3.68 0 0 1-1.18-2.9V29.36c0-1.05.4-2.1 1.05-2.9a4.09 4.09 0 0 1 2.9-1.18h16.98c1.06 0 1.98.4 2.77 1.05a3.87 3.87 0 0 1 1.18 2.9c0 1.19-.26 2.1-1.05 2.9-.66.79-1.58 1.18-2.9 1.18h-16.98v8.96h16.98c2.37 0 4.35-.4 5.93-1.32a12.9 12.9 0 0 0 6.32-7.38 14.17 14.17 0 0 0-.66-10.27 12.68 12.68 0 0 0-7.38-6.18c-1.58-.4-2.89-.66-4.2-.66h-17a11 11 0 0 0-.6-.02z"/>
    <path d="M488 0v42.27c0 1.44.13 2.9.65 4.34a10.97 10.97 0 0 0 3.95 5.8c.92.65 1.85 1.31 2.77 1.7a14.7 14.7 0 0 0 5.8 1.32h16.85c1.7 0 3.16-.26 4.34-.65a12.18 12.18 0 0 0 5.93-3.95 14.6 14.6 0 0 0 1.58-2.77 12.84 12.84 0 0 0 1.31-5.8V29.64c.14-2.77-.65-5.54-2.23-7.77a10.8 10.8 0 0 0-2.37-2.5 14.17 14.17 0 0 0-2.77-1.72 14.73 14.73 0 0 0-5.8-1.18h-16.85v9.35h16.86c1.18 0 2.1.4 2.76 1.05a4 4 0 0 1 1.05 2.77v12.64c0 1.05-.39 1.97-1.05 2.76a3.98 3.98 0 0 1-2.76 1.05h-16.86a4.23 4.23 0 0 1-2.76-1.05 3.76 3.76 0 0 1-1.06-2.76V0z"/>
    <path d="M552.65 3.69c-1.45 0-2.9.26-4.35.65a11.8 11.8 0 0 0-7.5 6.85 13.63 13.63 0 0 0-1.19 5.66v25.42c-.13 1.44.13 2.9.53 4.34a11.2 11.2 0 0 0 4.08 5.8 9.78 9.78 0 0 0 2.76 1.7c1.72.8 3.7 1.32 5.67 1.32h33.84c1.32 0 2.5-.52 3.42-1.31a5.54 5.54 0 0 0 1.32-3.42V29.63a5.3 5.3 0 0 0-1.32-3.43 4.65 4.65 0 0 0-3.42-1.31h-29.63v9.34h25.02v11.85h-29.23c-1.19 0-2.1-.39-2.77-1.05-.65-.66-.92-1.58-.92-2.76V16.98c0-1.31.27-2.23.92-2.9.66-.65 1.58-.91 2.77-.91h38.18V3.69z"/>
    <path d="M611.43 16.44c-1.8 0-3.67.5-5.32 1.33a11.58 11.58 0 0 0-6.2 7.38 12.9 12.9 0 0 0-.78 4.21v13.17c0 2.37.52 4.34 1.32 5.92a12.9 12.9 0 0 0 7.37 6.33c1.45.39 2.76.65 4.22.65h16.98v-8.95h-16.86c-1.18 0-2.1-.4-2.9-1.05a3.7 3.7 0 0 1-1.18-2.9V29.36a4 4 0 0 1 1.06-2.9 4.09 4.09 0 0 1 2.9-1.18h16.98c1.05 0 1.98.4 2.77 1.05a3.87 3.87 0 0 1 1.18 2.9c0 1.19-.4 2.1-1.05 2.9a3.67 3.67 0 0 1-2.9 1.18h-16.98v8.96h16.98c2.37 0 4.35-.4 5.93-1.32a12.91 12.91 0 0 0 6.32-7.38 14.17 14.17 0 0 0-.66-10.27 12.68 12.68 0 0 0-7.38-6.18c-1.58-.4-2.9-.66-4.21-.66h-16.98c-.2-.02-.4-.02-.61-.02z"/>
    <path d="M652.63 16.45a4.43 4.43 0 0 0-4.39 4.62v34.36h9.35V25.81h16.33a8.5 8.5 0 0 1 3.16.52 7.74 7.74 0 0 1 4.88 7.5v21.6h9.48v-21.6c0-2.23-.27-4.07-.8-5.78a16.96 16.96 0 0 0-2.23-4.35 12.07 12.07 0 0 0-3.03-3.3 18.46 18.46 0 0 0-3.69-2.23c-2.37-1.19-5-1.71-7.64-1.71h-21.42z"/>
    <path d="M40.68 10.53v26.6l-6.97 10.4L10.27 61.1a46.6 46.6 0 0 1-1.05-9.61 42.27 42.27 0 0 1 31.46-40.96zM49.9 0C23.17 0 .13 23.57.13 51.49A51.28 51.28 0 0 0 34.1 99.94v-9.87a42.4 42.4 0 0 1-20.8-20.15l26.73-15.54.65-.92 9.22-13.7V0zm6.06 0v99.94a50 50 0 0 0 48.32-49.9c0-10.4-3.02-20.02-8.55-28.05l-7.64 4.48a41.63 41.63 0 0 1 7.37 23.57 40.6 40.6 0 0 1-12.37 29.23V8.95a72.22 72.22 0 0 0-8.83-5v82.03a42.02 42.02 0 0 1-9.48 3.82V1.05C62.02.4 58.98.13 55.96 0z"/>
  </g>
</svg>	
		</div>

		<div class="wrapper">
			<div class="title" style="height: 15px"></div>

			<div class="content">
				
				<div id="loading" style="display:none">
    				<img src="img/loader.svg" class="loader">
    				<div style="text-align:center; margin-bottom: 15px; color: #999">Processing...</div>
        		</div>
        		
        		<?php
        		if(!isset($_SESSION['pwd']) && !$debug_mode) {
				    include_once('upload.php');
				} else {
    				if($debug_mode) {
        				include_once('processing.php');
        			} else { ?>
        			<script>$(function(){ loadProcessing(); });</script>
        			<?php }?>
        			
        		<?php 	
			    }?>

    		</div>

		</div>
		
		<div class="copyright">Dominique Roduit - EPFL &copy; 2018</div>
		
		
		<?php if($debug_mode) { ?>
		<div class="debug-area">
			<div class="debug-message">Debug mode</div>
			<div class="debug-tools">
				<div class="item gen-stamp" json_filename="<?= $json_filename ?>">Generate new stamp</div>
			</div>
		</div>
		<?php } ?>
		
		<div class="modal-layer"></div>
		
		<div class="modal-win">
			<div class="content">
				<div style="text-align:center">
					Please enter the password<br>
					<input type="password" id="pwd" />
					<div class="result"></div>
				</div>
			</div>
			<div class="bt-ok">Unlock</div>
		</div>
		
		<div class="message" style="display:none; position:fixed; bottom:0; left:0; width:100%; padding: 10px; text-align:center; background:rgba(0,0,0,0.8); color:white; font-size:0.8em"></div>

	</body>
	
</html>
<?php
} else {
    if(isset($_POST['genStamp'])) {
        if(isJSON($_POST['json_filename'])) {
            genStamp($_POST['json_filename'], $_POST['pwd']);
            echo 'New stamp succesfuly created';
        } else {
            echo "Le fichier à parser n'est pas un fichier JSON";
        }
    }
}
?>
