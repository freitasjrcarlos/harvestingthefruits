var SOM_TIRO = new Audio();
SOM_TIRO.src = 'snd/tiro.mp3';
SOM_TIRO.volume = 0.2;
SOM_TIRO.load();

function Tiro(context, fazendeiro) {
   this.context = context;
   this.fazendeiro = fazendeiro;
   
   // Posicionar o tiro no bico da fazendeiro
   this.largura = 3;
   this.altura = 10;   
   this.x = fazendeiro.x + 18;  // 36 / 2
   this.y = fazendeiro.y - this.altura;
   this.velocidade = 400;
   
   this.cor = 'yellow';
   SOM_TIRO.currentTime = 0.0;
   SOM_TIRO.play();
}
Tiro.prototype = {
   atualizar: function() {
      this.y -= 
         this.velocidade * this.animacao.decorrido / 1000;
      
      // Excluir o tiro quando sumir da tela
      if (this.y < -this.altura) {
         this.animacao.excluirSprite(this);
         this.colisor.excluirSprite(this);
      }
   },
   desenhar: function() {
      var ctx = this.context;
      ctx.save();
      ctx.fillStyle = this.cor;
      ctx.fillRect(this.x, this.y, this.largura, this.altura);
      ctx.restore();
   },
   retangulosColisao: function() {
      return [ {x: this.x, y: this.y, largura: this.largura,
            altura: this.altura} ];
   },
   colidiuCom: function(outro) {
   
   }
}
