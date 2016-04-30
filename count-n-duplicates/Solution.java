import java.util.HashMap;
import java.util.Map;

class Solution {

    public static int solve(int[] A, int n) {
        HashMap<Integer, Integer> counts = new HashMap<Integer, Integer>();
        int i;

        for (int a : A) {
            i = counts.containsKey(a) ? counts.get(a) + 1 : 1;
            counts.put(a, i);
        }

        int num_n_duplicates = 0;

        for (Map.Entry<Integer, Integer> c : counts.entrySet()) {
            if (n == c.getValue()) num_n_duplicates++;
        }

        return num_n_duplicates;
    }

    public static void main(String[] args) {
        int[] A = {0, 0, 1, 1, 1, 2, 2, 3, 3, 3};

        System.out.println("Starting...");

        // Should return 2
        System.out.println(Solution.solve(A, 2));

        // Should return 2
        System.out.println(Solution.solve(A, 3));

        // Should return 0
        System.out.println(Solution.solve(A, 0));
    }
}