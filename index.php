<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Street Cars by Crocciari Daniel</title>
    <style>
        canvas {
            border: 1px solid black;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <canvas id="gameCanvas" width="400" height="400"></canvas>
    <div id="carrosConquistados"></div>
    <script>
        const canvas = document.getElementById("gameCanvas");
        const ctx = canvas.getContext("2d");

        // Carregar imagens do jogador e dos alvos
        const playerImg = new Image();
        playerImg.src = 'player.png';

        const alvoImgs = [];
        for (let i = 1; i <= 5; i++) {
            const img = new Image();
            img.src = `alvo${i}.png`;
            alvoImgs.push(img);
        }

        // Definições do jogador
        const playerWidth = 50;
        const playerHeight = 50;
        let playerX = (canvas.width - playerWidth) / 2;
        const playerY = canvas.height - playerHeight - 10;

        // Variáveis de controle
        let rightPressed = false;
        let leftPressed = false;
        let gameOver = false;
        let alvos = [];
        let carrosDescendo = 0;
        let carrosConquistados = 0; // Contador de carros conquistados
        let velocidadeAlvos = 2; // Velocidade inicial dos alvos
        const velocidadeIncremento = 0.1; // Aumento de velocidade a cada 50 carros

        // Função para desenhar o jogador
        function drawPlayer() {
            ctx.drawImage(playerImg, playerX, playerY, playerWidth, playerHeight);
        }

        // Função para desenhar os alvos
        function drawAlvos() {
            for (let i = 0; i < alvos.length; i++) {
                const alvo = alvos[i];
                ctx.drawImage(alvo.img, alvo.x, alvo.y, alvo.width, alvo.height);
            }
        }

        // Função para desenhar o texto na tela
        function drawText() {
            if (gameOver) {
                ctx.font = "30px Arial";
                ctx.fillStyle = "#000000";
                ctx.fillText("Game Over", 140, 200);
                ctx.font = "20px Arial";
                ctx.fillText("BARRA DE ESPAÇO para recomeçar", 70, 250);
                // Exibir carros conquistados
                ctx.fillText("Conquista: " + carrosConquistados, 50, 300);
            } else {
                ctx.font = "20px Arial";
                ctx.fillStyle = "#000000";
                ctx.fillText("Carros: " + carrosDescendo, 10, 30);
            }
        }

        // Função para desenhar o jogo
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawPlayer();
            if (!gameOver) {
                drawAlvos();
                if (rightPressed && playerX < canvas.width - playerWidth) {
                    playerX += 5;
                } else if (leftPressed && playerX > 0) {
                    playerX -= 5;
                }
                moveAlvos();
                checkCollision();
            }
            drawText();
        }

        // Função para criar novos alvos
        function createAlvo() {
            const alvoWidth = 50; // Ajustando o tamanho dos alvos para ser igual ao do jogador
            const alvoHeight = 50;
            const randomIndex = Math.floor(Math.random() * alvoImgs.length);
            const alvoImg = alvoImgs[randomIndex];
            const alvoX = Math.random() * (canvas.width - alvoWidth);
            alvos.push({ img: alvoImg, x: alvoX, y: 0, width: alvoWidth, height: alvoHeight });
        }

        // Função para mover os alvos
        function moveAlvos() {
            for (let i = 0; i < alvos.length; i++) {
                alvos[i].y += velocidadeAlvos; // Velocidade dos alvos
                if (alvos[i].y > canvas.height) {
                    alvos.splice(i, 1);
                    i--;
                    carrosDescendo++;
                }
            }
        }

        // Função para verificar colisões entre o jogador e os alvos
        function checkCollision() {
            for (let i = 0; i < alvos.length; i++) {
                const alvo = alvos[i];
                if (playerX < alvo.x + alvo.width &&
                    playerX + playerWidth > alvo.x &&
                    playerY < alvo.y + alvo.height &&
                    playerY + playerHeight > alvo.y) {
                    gameOver = true;
                    break;
                }
            }
        }

        // Função para lidar com as teclas pressionadas
        function keyDownHandler(e) {
            if (e.key === "Right" || e.key === "ArrowRight") {
                rightPressed = true;
            } else if (e.key === "Left" || e.key === "ArrowLeft") {
                leftPressed = true;
            }
        }

        // Função para lidar com as teclas liberadas
        function keyUpHandler(e) {
            if (e.key === "Right" || e.key === "ArrowRight") {
                rightPressed = false;
            } else if (e.key === "Left" || e.key === "ArrowLeft") {
                leftPressed = false;
            } else if (e.key === " ") { // Barra de espaço para reiniciar o jogo
                if (gameOver) {
                    restartGame();
                }
            }
        }

        // Função para reiniciar o jogo
        function restartGame() {
            playerX = (canvas.width - playerWidth) / 2;
            alvos = [];
            gameOver = false;
            carrosDescendo = 0;
            carrosConquistados = 0;
            velocidadeAlvos = 2;
        }

        document.addEventListener("keydown", keyDownHandler, false);
        document.addEventListener("keyup", keyUpHandler, false);

        setInterval(draw, 10);
        setInterval(createAlvo, 1000); // Cria um novo alvo a cada segundo

        // Verifica a cada carro conquistado se já foram 50 e aumenta a velocidade dos alvos
        setInterval(() => {
            if (carrosConquistados % 50 === 0 && carrosConquistados !== 0) {
                velocidadeAlvos += velocidadeIncremento;
            }
        }, 100);

        // Atualiza o contador de carros conquistados
        setInterval(() => {
            if (!gameOver) {
                carrosConquistados = carrosDescendo;
            }
        }, 100);
    </script>
</body>
</html>
