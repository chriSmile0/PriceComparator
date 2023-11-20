CC=gcc
CFLAGS= -Wall -Werror -Wextra
LIBFLAGS= -lm
SRC = "src/"
INC = "inc/"
BIN	= "bin/"

all : proj

proj:
	$(CC) $(CFLAGS) $(SRC)*.c -o $(BIN)main $(LIBFLAGS)

exec: all 
	./bin/main

clean: 
	$(RM) $(BIN)main