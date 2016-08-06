#include <math.h>
#include <stdio.h>
#include <stdlib.h>

int precedence(char* o);
char associativity(char* o);
char** shunt(char* tokens[], int size);
float compute_rpn(char** tokens, int size);
float compute_operator(char* o, float l, float r);

int precedence(char* o)
{
    if ('^' == *o) return 2;
    if ('*' == *o || 'x' == *o || '/' == *o) return 1;
    if ('+' == *o || '-' == *o) return 0;
    return -1;
}

char associativity(char* o)
{
    if ('^' == *o) return 'r';
    return 'l';
}

char** shunt(char* tokens[], int size, char* out[])
{
    int out_ptr = 0;

    char* ops[size];
    int op_ptr = 0;

    int i;
    float num;

    char* o1;
    char* o2;

    for (i = 0; i < size; i++) {
        num = atof(tokens[i]);

        // If the token is a number, add it to the output queue.
        if ('0' == *tokens[i] || abs(num) > 0) {
            out[out_ptr] = tokens[i];
            out_ptr++;
            continue;
        }

        // If the token is not a number, then it is an operator...
        o1 = tokens[i];

        // While there is an operator o2 atop the operator stack and either
        // - o1 is left associative, and precedence(o1) <= precedence(o2)
        // - o1 is right-associative, and precedence(o1) < precedence(o2)
        // Pop o2 off the stack onto the output queue
        while (op_ptr > 0 && (o2 = ops[op_ptr - 1])) {
            if (
                ( associativity(o1) == 'l' && precedence(o1) <= precedence(o2) )
                || ( associativity(o1) == 'r' && precedence(o1) < precedence(o2) )
            ) {
                out[out_ptr] = o2;
                out_ptr++;
                op_ptr--;
            } else {
                break;
            }
        }

        // Push the operator onto the operator stack.
        ops[op_ptr] = o1;
        op_ptr++;
    }

    // Dump the rest of the operators onto the output stack
    while (op_ptr > 0) {
        out[out_ptr] = ops[op_ptr - 1];
        out_ptr++;
        op_ptr--;
    }

    return out;
}

float compute_rpn(char** tokens, int size) {
    int i;
    float l, r, num;
    int n_ptr = 0;
    float ns[size];
    char* o;

    for (i = 0; i < size; i++) {
        num = atof(tokens[i]);

        // If the token is a number, add it to the number stack
        if ('0' == *tokens[i] || abs(num) > 0) {
            ns[n_ptr] = num;
            n_ptr++;
            continue;
        }

        // The token is an operator (o); we pop two numbers (r, l) from the
        // number stack, compute l o r and push its value to the number stack
        l = ns[n_ptr - 2];
        o = tokens[i];
        r = ns[n_ptr - 1];

        ns[n_ptr - 2] = compute_operator(o, l, r);
        n_ptr--;
    }

    return ns[0];
}

float compute_operator(char* o, float l, float r) {
    // printf("%d%s%d", l, o, r);
    if ('*' == *o || 'x' == *o) return l * r;
    if ('/' == *o) return l / r;
    if ('+' == *o) return l + r;
    if ('-' == *o) return l - r;
    if ('^' == *o) return powf(l, r);
    return 0;
}

int main(int argc, char* argv[])
{
    int i;
    int size = argc - 1;

    for (i = 1; i < argc; i++) {
        argv[i - 1] = argv[i];
    }

    char* rpn[size];
    shunt(argv, size, rpn);

    // for (i = 0; i < size; i++) {
    //     printf("%s\n", rpn[i]);
    // }

    printf("%f\n", compute_rpn(rpn, size));
}

