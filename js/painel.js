function Painel(context, fazendeiro) {
   this.context = context;
   this.fazendeiro = fazendeiro;
   this.spritesheet = new Spritesheet(context, fazendeiro.imagem, 3, 2);
   this.pontuacao = 0;
}
Painel.prototype = {
   atualizar: function() {
      
   },
   desenhar: function() {
      // Reduz o desenho pela metade
      this.context.scale(0.5, 0.5);
      
      var x = 20;
      var y = 20;
      
      /*for (var i = 1; i <= this.fazendeiro.vidasExtras; i++) {
         this.spritesheet.desenhar(x, y);
         x += 40;
      }*/
      
      // Torna a dobrar
      this.context.scale(2, 2);
      
      // Para facilitar um pouco...
      var ctx = this.context;
      
      // Pontuação
      ctx.save();
      ctx.fillStyle = ' BLUE';
      ctx.font = '24px sans-serif';
      ctx.fillText(this.pontuacao, 12, 20);
      ctx.restore();   
   }
}
