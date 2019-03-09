<?php include 'header.php' ?>
   <script src="js/animacao.js"></script>
   <script src="js/teclado.js"></script>
   <script src="js/colisor.js"></script>
   <script src="js/fundo.js"></script>
   <script src="js/fazendeiro.js"></script>
   <script src="js/maca.js"></script>
   <script src="js/tiro.js"></script>
   <script src="js/spritesheet.js"></script>
   <script src="js/explosao.js"></script>
   <script src="js/painel.js"></script>
   <style>
#link_jogar {
	/* Inicia oculto */
	display: none;
	/* Cores e fundo */
	color: null;
	background: url(img/botao-jogar.png);
	/* Fonte */
	font-size: 20px;
	font-family: sans-serif;
	/* Sem sublinhado e com sombra */
	text-decoration: none;
	text-shadow: 2px 2px 5px black;
	/* Posicionamento */
	position: absolute;
	left: 620px;
	top: 250px;
	/* A imagem do botão é 72x72, descontamos os paddings */
	width: 90px;
	height: 38px;
	padding: 40px 55px;
}
</style>
   </head>

   <body>
<center>
     <canvas id="canvas_animacao" width="500" height="500"></canvas>
   </center>
<a id="link_jogar" href="javascript: iniciarJogo()" align=center></a> 

<!--Botões -->

<center>
     <a href="index.php"> <img src="img/desistir.png" width="120" height="50"> </a> <a href="instrucoes.php"><img src="img/instrucoes.png" width="120" height="50"></a>
   </center>
<script>
      // Canvas e Context
      var canvas = document.getElementById('canvas_animacao');
      var context = canvas.getContext('2d');

      // Variáveis principais
      var imagens, animacao, teclado, colisor, fazendeiro, criadorInimigos;
      var totalImagens = 0, carregadas = 0;
      var musicaAcao;
      
      // Começa carregando as imagens e músicas
      carregarImagens();
      carregarMusicas();
      
      function carregarImagens() {
         // Objeto contendo os nomes das imagens
         imagens = {
            espaco:   'fundo-laranjeira.png', 
            estrelas: 'fundo-estrelas.png', 
            nuvens:   'fundo-nuvens.png', 
            fazendeiro:     'fazendeiro-spritesheet.png', 
            maca:     'laranja.png',
            explosao: 'explosao.png'
         };
         
         // Carregar todas
         for (var i in imagens) {
            var img = new Image();
            img.src = 'img/' + imagens[i];
            img.onload = carregando;
            totalImagens++;
            
            // Substituir o nome pela imagem
            imagens[i] = img;
         }
      }
      
      function carregando() {
         context.save();
         
         // Fundo 
         context.drawImage(imagens.espaco, 0, 0, canvas.width,
                           canvas.height);
         
         /*// Texto "Carregando"
         context.fillStyle = 'white';
         context.strokeStyle = 'black';
         context.font = '50px sans-serif';
         context.fillText("Carregando...", 100, 200);
         context.strokeText("Carregando...", 100, 200);
         
         // Barra de loading*/
         carregadas++;
         var tamanhoTotal = 300;
         var tamanho = carregadas / totalImagens * tamanhoTotal;
         /*context.fillStyle = 'yellow';
         context.fillRect(100, 250, tamanho, 50);*/ 
         
         context.restore();         
         
         if (carregadas == totalImagens) {
            iniciarObjetos();
            mostrarLinkJogar();
         }
      }
      
      function iniciarObjetos() {
         // Objetos principais
         animacao = new Animacao(context);
         teclado = new Teclado(document);
         colisor = new Colisor();
         espaco = new Fundo(context, imagens.espaco);
         estrelas = new Fundo(context, imagens.estrelas);
         nuvens = new Fundo(context, imagens.nuvens);
         fazendeiro = new Fazendeiro(context, teclado, imagens.fazendeiro, 
                         imagens.explosao);
         painel = new Painel(context, fazendeiro);
         
         // Ligações entre objetos
         animacao.novoSprite(espaco);
         animacao.novoSprite(estrelas);
         animacao.novoSprite(nuvens);
         animacao.novoSprite(painel);
         animacao.novoSprite(fazendeiro);
         
         colisor.novoSprite(fazendeiro);
         animacao.novoProcessamento(colisor);
         
         configuracoesIniciais();
      }
      
      function configuracoesIniciais() {
         // Fundos
         espaco.velocidade = 0;
         estrelas.velocidade = 0;
         nuvens.velocidade = 0;
         
         // Fazendeiro
         fazendeiro.posicionar();
         fazendeiro.velocidade = 200;
         
         // Inimigos
         criacaoInimigos();
         
         // Game Over
         fazendeiro.acabaramVidas = function() {
            animacao.desligar();
            gameOver();
         }
         
         // Pontuação
         colisor.aoColidir = function(o1, o2) {
            // Tiro com Maca
            if ( (o1 instanceof Tiro && o2 instanceof Maca) ||
                 (o1 instanceof Maca && o2 instanceof Tiro) )
               painel.pontuacao += 10;
         }
      }
      
      function criacaoInimigos() {
         criadorInimigos = {
            ultimoMaca: new Date().getTime(),
            
            processar: function() {
               var agora = new Date().getTime();
               var decorrido = agora - this.ultimoMaca;
               
               if (decorrido > 1000) {
                  novoMaca();
                  this.ultimoMaca = agora;
               }
            }
         };
         
         animacao.novoProcessamento(criadorInimigos);
      }
      
      function novoMaca() {
         var imgMaca = imagens.maca;
         var maca = new Maca(context, imgMaca, imagens.explosao);

         // Mínimo: 500; máximo: 1000
         maca.velocidade = 
            Math.floor( 1500 + Math.random() * (2500 + 2500 + 2500) );
         
         // Mínimo: 0; máximo: largura do canvas - largura do maca   
         maca.x = 
            Math.floor(Math.random() * 
                       (canvas.width - imgMaca.width + 1) );
                  
         // Descontar a altura
         maca.y = -imgMaca.height;
         
         animacao.novoSprite(maca);
         colisor.novoSprite(maca);
      }
      
      function pausarJogo() {
         if (animacao.ligado) {
            animacao.desligar();
            ativarTiro(false);
            context.save();
            context.fillStyle = 'white';
            context.strokeStyle = 'black';
            context.font = '50px sans-serif';
            context.fillText("Pausado", 160, 200);
            context.strokeText("Pausado", 160, 200);
            context.restore();
         }
         else {
            criadorInimigos.ultimoMaca = new Date().getTime();
            animacao.ligar();
            ativarTiro(true);
         }
      }
      
      function ativarTiro(ativar) {
         if (ativar) {
            teclado.disparou(ESPACO, function() {
               fazendeiro.atirar();
            });
         }
         else
            teclado.disparou(ESPACO, null);
      }
      
      function carregarMusicas() {
         musicaAcao = new Audio();
         musicaAcao.src = 'snd/musica-acao.mp3';
         musicaAcao.volume = 0.8;
         musicaAcao.loop = true;
         musicaAcao.load();
      }
      
      function mostrarLinkJogar() {
         document.getElementById('link_jogar').style.display =
            'block';
      }
      
      function iniciarJogo() {
         criadorInimigos.ultimoMaca = new Date().getTime();
      
         // Tiro
         ativarTiro(true);
         
         // Pausa
         teclado.disparou(ENTER, pausarJogo);
         
         document.getElementById('link_jogar').style.display =
            'none';
         musicaAcao.play();
         animacao.ligar();
      }
      
      function gameOver() {
         // Tiro
         ativarTiro(false);
         
         // Pausa
         teclado.disparou(ENTER, null);
         
         // Parar a música e rebobinar
         musicaAcao.pause();
         musicaAcao.currentTime = 0.0;
         
         // Fundo
         context.drawImage(imagens.espaco, 0, 0, canvas.width,
                           canvas.height);
         
         // Texto "Game Over"
         context.save();
         context.fillStyle = 'white';
         context.strokeStyle = 'black';
         context.font = '70px sans-serif';
         context.fillText("GAME OVER", 40, 200);
         context.strokeText("GAME OVER", 40, 200);
         context.restore();
         
         // Volta o link "Jogar"
         mostrarLinkJogar();
         
         // Restaurar as condições da fazendeiro
         fazendeiro.vidasExtras = 3;
         fazendeiro.posicionar();
         animacao.novoSprite(fazendeiro);
         colisor.novoSprite(fazendeiro);
         
         // Tirar todos os inimigos da tela
         removerInimigos();
      }
      
      function removerInimigos() {
         for (var i in animacao.sprites) {
            if (animacao.sprites[i] instanceof Maca)
               animacao.excluirSprite(animacao.sprites[i]);
         }
      }
	  
	  
	  var myVar = setInterval(function(){ myTimer() }, 10000);

function myTimer() {
    var d = new Date();
    var t = d.toLocaleTimeString();
    document.getElementById("demo").innerHTML = t;
}

setInterval(function(){ gameWin(); }, 60000);
	
	  function gameWin(){
		var newLocation = window.location.href.split('/');
        newLocation.pop();
        newLocation.push('tempo.php');
        window.location = newLocation.join('/');
	  }
   </script>

