#include "../inc/fct.h"

/**
 * @version 1  
 * @brief 	Ouvrir target_coordinates.txt et passer à l'action 
 * 			afin de définir les différentes cibles dans le périmètre d'une 
 * 			autre
*/

int main(int argc, char *argv[]) {
	(void) argc;
	(void) argv;

	/*char target_city[100];
	int radius = 0;
	int coordinates_max_x;
	int coordinates_max_y;
	int coordinates_min_x;
	int coordinates_min_y;
	printf("****EXEC****\n");
	printf("Please choose a target city !\n");
	scanf("%s",target_city);

	//Check validity
	//Check validity 

	printf("Choose city : %s\n",target_city);

	printf("Choose a distance a kilometer distance for the comparaison field max 40km\n");*/

	/*circle_maxs *city;
	coordinates t_cds = {5.0,6.0};
	city = malloc(sizeof(circle_maxs));
	init_circle_maxs(city,5,t_cds);*/

	coordinates p1 = {45.692612044184465, 5.907440240448223};// Aix les bains
	coordinates p2 = {45.5648362120869, 5.916068354895169};// Chambéry */
	coordinates p1_p = {48.85646701996376, 2.3506377952481885}; //Paris
	//coordinates p2_m = {55.766960903083174, 37.548897653404985}; // Moscou 
	//coordinates p1_puynormand = {45.0, 0.0};
	/*coordinates tp1 = {2,51};
	coordinates tp2 = {3,51};*/
	float distance_p1_p2 = calc_D_Earth2Points(p1,p2);
	printf("Distance entre chambéry et aix les bains : %f\n",distance_p1_p2);

	// On cherche maintenant à inverser la formule de tel sorte que des coordonnees
	// plus une distance donne des nouvelles coordonnées en y ajoutant une direction 
	// en degrès -> 

	/*printf("ToNorth of Chambery : %f\n",ToNorth(p2,14.0));
	coordinates chamb_adegree = ToAnyDegree(p2,14.0,90);
	printf("ToAnyDegree of Chambery : %f,%f\n",chamb_adegree.y,chamb_adegree.x);*/

	coordinates paris_adegree = ToAnyDegree(p1_p,150,180);
	printf("ToAnyDegree of paris : N  : %f,%f\n",paris_adegree.y,paris_adegree.x);
	//printf("%f\n",(48.85646701996376+(150/R)*(2)));	



	return 0;
}