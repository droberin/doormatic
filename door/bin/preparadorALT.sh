#!/bin/sh
echo "[01;33mTratando de compilar el controlador de la cerradura."
gcc -o openDoor.bin openDoor.c
echo "Cambiando propietario y grupo."
chown root:www-data openDoor.bin
echo "Cambiando permisos a ejecución por www-data como si de root se tratase.[00;00m"
chmod 6770 openDoor.bin
echo "----------------------------------------------------------------"
ls -al openDoor.bin
echo "----------------------------------------------------------------"
echo "[01;31mEn caso de no haber leído ningún error, el prog está listo."
echo "Ahora, copia el fichero PHP en el dir correspondiente pero antes cambia las rutas en el comienzo del mismo.[00;00m"
