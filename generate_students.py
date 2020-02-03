from random import randint


def split(word):
    return [char for char in word]


imiona_meskie = [
    "Piotr",
    "Krzysztof",
    "Andrzej",
    "Tomasz",
    "Jan",
    "Paweł",
    "Michał",
    "Marcin",
    "Stanisław",
    "Jakub",
    "Adam",
    "Marek",
    "Łukasz",
    "Grzegorz",
    "Mateusz",
    "Wojciech",
    "Mariusz",
    "Dariusz",
    "Zbigniew",
    "Jerzy",
]

imiona_zenskie = [
    "Agata",
    "Alicja",
    "Roksana",
    "Dagmara",
    "Bronisława",
    "Eliza",
    "Celina",
    "Magdalena",
    "Klara",
    "Gertruda",
    "Mieczysława",
    "Paulina",
    "Aleksandra",
    "Ewa",
    "Ewelina",
    "Zofia",
    "Monika",
    "Anna",
    "Katarzyna",
    "Małgorzata",
]

nazwiska = [
    "Zając",
    "Pawłowski",
    "Michalski",
    "Król",
    "Wieczorek",
    "Jabłoński",
    "Wróbel",
    "Nowakowski",
    "Majewski",
    "Olszewski",
    "Stępień",
    "Malinowski",
    "Jaworski",
    "Adamczyk",
    "Dudek",
    "Nowicki",
    "Pawlak",
    "Górski",
    "Witkowski",
    "Walczak",
    "Sikora",
    "Baran",
    "Rutkowski",
    "Michalak",
    "Szewczyk",
    "Ostrowski",
    "Tomaszewski",
    "Pietrzak",
    "Zalewski",
    "Wróblewski",
    "Marciniak",
    "Jasiński",
    "Zawadzki",
    "Bąk",
    "Jakubowski",
    "Sadowski",
    "Duda",
    "Włodarczyk",
    "Wilk",
    "Chmielewski",
    "Borkowski",
    "Sokołowski",
    "Szczepański",
    "Sawicki",
    "Kucharski",
    "Lis",
    "Maciejewski",
    "Kubiak",
    "Kalinowski",
    "Mazurek",
    "Wysocki",
    "Kołodziej",
    "Kaźmierczak",
    "Czarnecki",
    "Sobczak",
    "Konieczny",
    "Urbański",
    "Głowacki",
    "Wasilewski",
    "Sikorski",
    "Zakrzewski",
    "Krajewski",
    "Krupa",
]

student_counter = 1
for i in range(1, 5):
    for j in range(0, randint(10, 16)):
        # Generate girls
        nazwisko = nazwiska[randint(0, len(nazwiska) - 1)]
        if nazwisko[-2:] == "ki":
            helper = split(nazwisko)
            helper[-1] = "a"
            nazwisko = "".join(helper)
        print(
            "(%d, '%s', '%s', '%d-%d-2002', %d),"
            % (
                student_counter,
                imiona_zenskie[randint(0, len(imiona_zenskie) - 1)],
                nazwisko,
                randint(1, 28),
                randint(1, 12),
                i,
            ),
        )
        student_counter = student_counter + 1

    for j in range(0, randint(10, 16)):
        # Generate boys
        print(
            "(%d, '%s', '%s', '%d-%d-2002', %d),"
            % (
                student_counter,
                imiona_meskie[randint(0, len(imiona_meskie) - 1)],
                nazwiska[randint(0, len(nazwiska) - 1)],
                randint(1, 28),
                randint(1, 12),
                i,
            ),
        )
        student_counter = student_counter + 1


