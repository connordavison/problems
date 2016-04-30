import java.util.Arrays;

class Solution {

  public static int solve(int[] A, int[] B) {
    int i = 0;
    int j = 0;

    Arrays.sort(A);
    Arrays.sort(B);

    int matches = 0;

    while (i < A.length && j < B.length) {
      if (A[i] == B[j]) {
        matches++;
        j++;
      } else if (A[i] < B[j]) {
        i++;
      } else {
        j++;
      }
    }

    return matches;
  }

  public static void main(String[] args) {
    int[] A = {1, 3, 9, 2};
    int[] B = {1, 3, 20, 5, 2};

    System.out.println("Starting");

    // 3 should match
    System.out.println(Solution.solve(A, B));

    int[] C = {0, 6, 12, 18, 24, 30};
    int[] D = {0, 12, 24, 36, 48, 60};

    // 3 should match
    System.out.println(Solution.solve(C, D));

    int[] E = {1, 2, 3, 4, 5, 6, 7};

    // 7 should match
    System.out.println(Solution.solve(E, E));

    int[] F = {-1, -2, -3, -4, -5, -6, -7};

    // 0 should match
    System.out.println(Solution.solve(E, F));
  }
}
