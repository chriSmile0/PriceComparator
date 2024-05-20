CC=gcc
CFLAGS= -Wall -Werror -Wextra
LIBFLAGS= -lm
SRC = "src/"
INC = "inc/"
BIN	= "bin/"

all : proj

proj:
	mkdir bin 2> /dev/null ;\
	$(CC) $(CFLAGS) $(SRC)*.c -o $(BIN)main $(LIBFLAGS)

exec: all # default 
	./bin/main firefox

exec_chrome: all 
	./bin/main google-chrome


clean: 
	-$(RM) -r $(BIN)