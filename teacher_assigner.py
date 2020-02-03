from random import randint

for i in range(1, 19):
    for j in range(0, randint(1, 3)):
        print(
            "((SELECT teacher_id FROM teachers WHERE teacher_id=%d),(SELECT subject_id FROM subjects WHERE subject_id=%d)),"
            % (randint(1, 9), i)
        )
