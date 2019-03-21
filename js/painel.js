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
      
      let x = 20;
      let y = 20;
      
      /* Vidas extras */
      /*for (var i = 1; i <= this.fazendeiro.vidasExtras; i++) {
         this.spritesheet.desenhar(x, y);
         x += 40;
      }*/
      
      // Torna a dobrar
      this.context.scale(2, 2);
      
      // Para facilitar um pouco...
      let ctx = this.context;
      
      // Pontuação
      ctx.save();
      ctx.fillStyle = ' #FF8C00';
      ctx.font = '24px sans-serif';
      ctx.fillText(this.pontuacao, 12, 20);
      ctx.restore();   
   }
}
