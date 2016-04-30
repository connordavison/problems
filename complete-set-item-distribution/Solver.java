import java.util.Arrays;

class Solver
{
    /**
     * Calculate the expected number of trials required to have seen each item
     * n in N, S times.
     *
     * @param N The items.
     * @param S The number of sets.
     * @return double
     */
    public static double solve(int N, int S)
    {
        int[] state = new int[N];
        Arrays.fill(state, 0);

        return branch(N, S, state);
    }

    /**
     * Calculate the expected number of trials required from a particular state
     * to have seen each item n in N
     *
     * @param N The items.
     * @param S The number of sets.
     * @param state The state such that the state[i] is the number of item i
     * already received.
     * @return double The expected number of trials from this frame and all
     * subframes.
     */
    public static double branch(int N, int S, int[] state)
    {
        int remaining = 0;
        double expecteds = 0;

        for (int i = 0; i < N; i++) {
            if (state[i] < S) {
                remaining++;

                int[] substate = state.clone();
                substate[i]++;
                expecteds += branch(N, S, substate);
            }
        }

        if (0 == remaining) {
            return 0;
        } else {
            return (expecteds + N) / remaining;
        }
    }

    public static void main(String[] args) {
        for (int n = 1; n < 26; n++) {
            for (int s = 1; s < 26; s++) {
                System.out.print(solve(n, s));
                System.out.print(",");
            }
            System.out.print("\n");
        }
    }
}