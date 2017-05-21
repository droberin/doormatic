#!/bin/bash

#Frecuencias
DO="261.6" && RE="293.7" && MI="329.6" && FA="349.2" 
SOL="392.0" && LA="440.0" && SI="493.9"
DO5="523.25" && SIb="466.16"

#Tempo
TEMPO="400" # Milisegundos

#Duración
NEGRA=$[1 * $TEMPO]
BLANCA=$[$NEGRA*2]
CORCHEA=$[$NEGRA/2]

beep -f $DO -l $CORCHEA \
-n -f $DO -l $CORCHEA \
-n -f $RE -l $NEGRA \
-n -f $DO -l $NEGRA \
-n -f $FA -l $NEGRA \
-n -f $MI -l $BLANCA \
-n -f $DO -l $CORCHEA \
-n -f $DO -l $CORCHEA \
-n -f $RE -l $NEGRA \
-n -f $DO -l $NEGRA \
-n -f $SOL -l $NEGRA \
-n -f $FA -l $BLANCA \
-n -f $DO -l $CORCHEA \
-n -f $DO -l $CORCHEA \
-n -f $DO5 -l $NEGRA \
-n -f $LA -l $NEGRA \
-n -f $FA -l $NEGRA \
-n -f $MI -l $NEGRA \
-n -f $RE -l $NEGRA \
-n -f $SIb -l $CORCHEA \
-n -f $SIb -l $CORCHEA \
-n -f $LA -l $NEGRA \
-n -f $FA -l $NEGRA \
-n -f $SOL -l $NEGRA \
-n -f $FA -l $BLANCA

