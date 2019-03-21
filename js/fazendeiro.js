function Fazendeiro(context, teclado, imagem, imgExplosao) {
   this.context = context;
   this.teclado = teclado;
   this.imagem = imagem;
   this.x = 0;
   this.y = 0;
   this.velocidade = 0;
   this.spritesheet = new Spritesheet(context, imagem, 3, 2);
   this.spritesheet.linha = 0;
   this.spritesheet.intervalo = 100;
   this.imgExplosao = imgExplosao;
   this.acabaramVidas = null;
   this.vidasExtras = 3;
}
Fazendeiro.prototype = {
   atualizar: function() {
      var incremento = 
          this.velocidade * this.animacao.decorrido / 500;
      
      if (this.teclado.pressionada(SETA_ESQUERDA) && this.x > 0)
         this.x -= incremento;
         
      if (this.teclado.pressionada(SETA_DIREITA) && 
               this.x < this.context.canvas.width - 36)
         this.x += incremento;
         
      //Caso querer adicionar posteriormente
      if (this.teclado.pressionada(SETA_ACIMA) && this.y > 0)
         this.y -= incremento;
         
      if (this.teclado.pressionada(SETA_ABAIXO) &&
               this.y < this.context.canvas.height - 48)
         this.y += incremento;
   },
   desenhar: function() {
      if (this.teclado.pressionada(SETA_ESQUERDA))
         this.spritesheet.linha = 1;
      else if (this.teclado.pressionada(SETA_DIREITA))
         this.spritesheet.linha = 2;
      else
         this.spritesheet.linha = 0;      
      
      this.spritesheet.desenhar(this.x, this.y);
      this.spritesheet.proximoQuadro();
   },
   atirar: function() {
      let t = new Tiro(this.context, this);
      this.animacao.novoSprite(t);
      this.colisor.novoSprite(t);
   },
   retangulosColisao: function() {
      // Estes valores vão sendo ajustados aos poucos
      let rets = 
      [ 
         
         {x: this.x+20, y: this.y+98, largura: 75, altura: 58}
      ];
      
      /*// Desenhando os retângulos para visualização
      
      var ctx = this.context;
      
      for (var i in rets) {
         ctx.save();
         ctx.strokeStyle = 'yellow';
         ctx.strokeRect(rets[i].x, rets[i].y, rets[i].largura, 
                        rets[i].altura);
         ctx.restore();
      }*/
      
      
      return rets;
   },
   colidiuCom: function(outro) {
      // Se colidiu com uma maçã
      if (outro instanceof Maca) {
         this.animacao.excluirSprite(this);
         this.animacao.excluirSprite(outro);
         this.colisor.excluirSprite(this);
         this.colisor.excluirSprite(outro);
         
         let exp1 = new Explosao(this.context, this.imgExplosao,
                                 this.x, this.y);
         let exp2 = new Explosao(this.context, this.imgExplosao,
                                 outro.x, outro.y);
         
         this.animacao.novoSprite(exp1);
         this.animacao.novoSprite(exp2);
         
         var fazendeiro = this;
         exp1.fimDaExplosao = function() {
            fazendeiro.vidasExtras++;
			painel.pontuacao += 10;
			
			
            /* YOU WIN */
         
			
            if (painel.pontuacao >= 450) {
        let newLocation = window.location.href.split('/');
        newLocation.pop();
        newLocation.push('youWin.php');
        window.location = newLocation.join('/');
            }
            
            else {
               // Recolocar a fazendeiro no engine
               fazendeiro.colisor.novoSprite(fazendeiro);
               fazendeiro.animacao.novoSprite(fazendeiro);
               
               /*fazendeiro.posicionar();*/
            }
         }
      }
   },
   posicionar: function() {
      let canvas = this.context.canvas;
      this.x = canvas.width / 2 - 18;  // 36 / 2
      this.y = canvas.height - 154;
   }
}
