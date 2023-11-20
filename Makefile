CC=gcc
CFLAGS= -Wall -Werror -Wextra
LIBFLAGS= -lm
SRC = "src/"
INC = "inc/"
BIN	= "bin/"

all : proj

proj:
	mkdir ${BIN} 2> /dev/null 
	$(CC) $(CFLAGS) $(SRC)*.c -o $(BIN)main $(LIBFLAGS)

exec: all 
	./bin/main

clean: 
	-$(RM) -r $(BIN)