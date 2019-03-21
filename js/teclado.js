// Códigos de teclas - aqui vão todos os que forem necessários
let SETA_ESQUERDA = 37;
let SETA_ACIMA = 0;
let SETA_DIREITA = 39;
let SETA_ABAIXO = 0;
let ESPACO = 0;
let ENTER = 13;

function Teclado(elemento) {
   this.elemento = elemento;

   // Array de teclas pressionadas
   this.pressionadas = [];

   // Array de teclas disparadas
   this.disparadas = [];

   // Funções de disparo registradas
   this.funcoesDisparo = [];

   let teclado = this;

   elemento.addEventListener('keydown', function(evento) {
      let tecla = evento.keyCode;  // Tornando mais legível ;)
      teclado.pressionadas[tecla] = true;

      // Disparar somente se for o primeiro keydown da tecla
      if (teclado.funcoesDisparo[tecla] && !teclado.disparadas[tecla]) {

          teclado.disparadas[tecla] = true;
          teclado.funcoesDisparo[tecla] () ;
      }
   });

   elemento.addEventListener('keyup', function(evento) {
      teclado.pressionadas[evento.keyCode] = false;
      teclado.disparadas[evento.keyCode] = false;
   });
}
Teclado.prototype = {
   pressionada: function(tecla) {
      return this.pressionadas[tecla];
   },
   disparou: function(tecla, callback) {
      this.funcoesDisparo[tecla] = callback;
   }
}
