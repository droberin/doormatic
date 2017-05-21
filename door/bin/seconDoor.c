
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/io.h>

main(int argc, char **argv)
{                    
  fprintf(stderr, "%d\n",(unsigned char)argv[1]);
  
}
