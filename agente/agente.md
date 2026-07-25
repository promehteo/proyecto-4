REGLA CERO (.gitignore solo lectura): NUNCA edites, commitees ni publiques
cambios en .gitignore. El resultado de cualquier evaluacion del .gitignore
va al chat, no al archivo. Antes de cada write declara el path; si es
.gitignore, aborta esa operacion.

SOBRE LA PUBLICACION (importante, leelo):
- Este workspace NO tiene remoto "origin" configurado; NO intentes
  `git fetch origin` ni `git pull --rebase origin ...`: fallaran. La
  publicacion a GitHub la hace el boton "Publish to GitHub" de la UI, no
  un git push tuyo.
- Por tanto, NO puedes "sincronizarte" por git. Si el "Publish" es
  rechazado ("actualizacion de la rama rechazada por el servidor remoto"),
  ese mensaje es generico y NO es de credenciales: significa que el remoto
  avanzo por detras de este workspace. En ese caso: NO reintentes a ciegas,
  NO hagas `git push --force` (aunque pudieras), y NO te quedes en bucle.
  Avisame con una linea ("Publish rechazado: remoto divergente") y yo abro
  sesion nueva; no intentes arreglarlo tu desde aqui.
- Si el check de GitHub "Proteger gitignore (guard)" sale en rojo, o te
  dicen que el .gitignore esta tocado, descartalo SOLO de tus cambios
  locales (esto si puedes, es tu working tree):
      git restore --staged .gitignore 2>/dev/null; git restore .gitignore
  o, si ya lo commiteaste, deshaz solo ese archivo contra el commit anterior
  y commitea el arreglo:
      git checkout HEAD~1 -- .gitignore
      git commit -m "revert: descarto cambio accidental en .gitignore"
  y vuelve a publicar. Nunca toques .gitignore a proposito.

Al final de cada tarea muestra: git log --oneline -5 y git status, y
confirma que .gitignore no aparece en `git status`.