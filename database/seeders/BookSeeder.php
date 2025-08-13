<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'published_year' => 1925,
                'summary' => 'A classic American novel set in the Jazz Age, exploring themes of wealth, love, and the American Dream.',
                'available' => true,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'published_year' => 1960,
                'summary' => 'A gripping tale of racial injustice and childhood innocence in the American South.',
                'available' => true,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'published_year' => 1949,
                'summary' => 'A dystopian social science fiction novel about totalitarian control and surveillance.',
                'available' => false,
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'published_year' => 1813,
                'summary' => 'A romantic novel that critiques the British landed gentry at the end of the 18th century.',
                'available' => true,
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'published_year' => 1951,
                'summary' => 'A controversial novel about teenage rebellion and alienation in post-war America.',
                'available' => true,
            ],
            [
                'title' => 'Moby-Dick',
                'author' => 'Herman Melville',
                'published_year' => 1851,
                'summary' => 'Captain Ahab embarks on an obsessive quest to hunt the white whale.',
                'available' => true,
            ],
            [
                'title' => 'War and Peace',
                'author' => 'Leo Tolstoy',
                'published_year' => 1869,
                'summary' => 'An epic portrayal of Russian society during the Napoleonic Wars.',
                'available' => false,
            ],
            [
                'title' => 'Crime and Punishment',
                'author' => 'Fyodor Dostoevsky',
                'published_year' => 1866,
                'summary' => 'A psychological study of guilt and redemption of a destitute ex-student.',
                'available' => true,
            ],
            [
                'title' => 'Ulysses',
                'author' => 'James Joyce',
                'published_year' => 1922,
                'summary' => 'A modernist, stream-of-consciousness journey through a single day in Dublin.',
                'available' => false,
            ],
            [
                'title' => 'Middlemarch',
                'author' => 'George Eliot',
                'published_year' => 1871,
                'summary' => 'Interwoven lives in a provincial town explore ambition, marriage, and reform.',
                'available' => true,
            ],
            [
                'title' => 'Brave New World',
                'author' => 'Aldous Huxley',
                'published_year' => 1932,
                'summary' => 'A dystopian vision of a technologically engineered, conformist society.',
                'available' => true,
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'published_year' => 1954,
                'summary' => 'An epic quest to destroy a powerful ring and defeat a dark lord.',
                'available' => true,
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'published_year' => 1937,
                'summary' => 'Bilbo Baggins joins a quest to reclaim a lost dwarf kingdom.',
                'available' => false,
            ],
            [
                'title' => 'Jane Eyre',
                'author' => 'Charlotte Brontë',
                'published_year' => 1847,
                'summary' => 'A governess seeks love and independence while confronting dark secrets.',
                'available' => true,
            ],
            [
                'title' => 'Wuthering Heights',
                'author' => 'Emily Brontë',
                'published_year' => 1847,
                'summary' => 'A turbulent tale of passion and revenge on the Yorkshire moors.',
                'available' => false,
            ],
            [
                'title' => 'Great Expectations',
                'author' => 'Charles Dickens',
                'published_year' => 1861,
                'summary' => 'An orphan named Pip navigates class, ambition, and identity.',
                'available' => true,
            ],
            [
                'title' => 'The Brothers Karamazov',
                'author' => 'Fyodor Dostoevsky',
                'published_year' => 1880,
                'summary' => 'Faith, doubt, and morality collide in a family drama and murder trial.',
                'available' => false,
            ],
            [
                'title' => 'Anna Karenina',
                'author' => 'Leo Tolstoy',
                'published_year' => 1877,
                'summary' => 'A tragic love affair exposes societal hypocrisy and personal struggle.',
                'available' => true,
            ],
            [
                'title' => 'One Hundred Years of Solitude',
                'author' => 'Gabriel García Márquez',
                'published_year' => 1967,
                'summary' => 'Magical realism chronicles the Buendía family in the town of Macondo.',
                'available' => true,
            ],
            [
                'title' => 'Don Quixote',
                'author' => 'Miguel de Cervantes',
                'published_year' => 1605,
                'summary' => 'A deluded knight-errant and his squire embark on comic adventures.',
                'available' => true,
            ],
            [
                'title' => 'The Divine Comedy',
                'author' => 'Dante Alighieri',
                'published_year' => 1320,
                'summary' => 'A visionary journey through Hell, Purgatory, and Paradise.',
                'available' => false,
            ],
            [
                'title' => 'Les Misérables',
                'author' => 'Victor Hugo',
                'published_year' => 1862,
                'summary' => 'Redemption and justice amidst the struggles of 19th-century France.',
                'available' => true,
            ],
            [
                'title' => 'The Count of Monte Cristo',
                'author' => 'Alexandre Dumas',
                'published_year' => 1844,
                'summary' => 'A wrongfully imprisoned man seeks revenge and justice.',
                'available' => true,
            ],
            [
                'title' => 'The Picture of Dorian Gray',
                'author' => 'Oscar Wilde',
                'published_year' => 1890,
                'summary' => 'A portrait ages and bears sins while its subject remains youthful.',
                'available' => false,
            ],
            [
                'title' => 'A Tale of Two Cities',
                'author' => 'Charles Dickens',
                'published_year' => 1859,
                'summary' => 'Love and sacrifice during the tumult of the French Revolution.',
                'available' => true,
            ],
            [
                'title' => 'The Stranger',
                'author' => 'Albert Camus',
                'published_year' => 1942,
                'summary' => 'An existential exploration of alienation and the absurd.',
                'available' => true,
            ],
            [
                'title' => 'The Sound and the Fury',
                'author' => 'William Faulkner',
                'published_year' => 1929,
                'summary' => 'A fragmented narrative of a Southern family’s decline.',
                'available' => false,
            ],
            [
                'title' => 'Lolita',
                'author' => 'Vladimir Nabokov',
                'published_year' => 1955,
                'summary' => 'A controversial tale of obsession and manipulation.',
                'available' => true,
            ],
            [
                'title' => 'The Sun Also Rises',
                'author' => 'Ernest Hemingway',
                'published_year' => 1926,
                'summary' => 'Expatriates seek meaning in the aftermath of World War I.',
                'available' => true,
            ],
            [
                'title' => 'Slaughterhouse-Five',
                'author' => 'Kurt Vonnegut',
                'published_year' => 1969,
                'summary' => 'A darkly comic meditation on war, trauma, and time.',
                'available' => false,
            ],
            [
                'title' => 'Fahrenheit 451',
                'author' => 'Ray Bradbury',
                'published_year' => 1953,
                'summary' => 'In a future society, books are outlawed and burned.',
                'available' => true,
            ],
            [
                'title' => 'Animal Farm',
                'author' => 'George Orwell',
                'published_year' => 1945,
                'summary' => 'A satirical allegory of revolution and totalitarianism.',
                'available' => true,
            ],
            [
                'title' => 'The Grapes of Wrath',
                'author' => 'John Steinbeck',
                'published_year' => 1939,
                'summary' => 'A Dust Bowl family migrates west seeking dignity and work.',
                'available' => false,
            ],
            [
                'title' => 'Of Mice and Men',
                'author' => 'John Steinbeck',
                'published_year' => 1937,
                'summary' => 'Two drifters chase a dream of a better life during the Depression.',
                'available' => true,
            ],
            [
                'title' => 'The Old Man and the Sea',
                'author' => 'Ernest Hemingway',
                'published_year' => 1952,
                'summary' => 'An aging fisherman battles a giant marlin in the Gulf Stream.',
                'available' => true,
            ],
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'published_year' => 1988,
                'summary' => 'A shepherd follows omens in search of his personal legend.',
                'available' => false,
            ],
            [
                'title' => 'The Kite Runner',
                'author' => 'Khaled Hosseini',
                'published_year' => 2003,
                'summary' => 'A story of friendship, betrayal, and redemption in Afghanistan.',
                'available' => true,
            ],
            [
                'title' => 'Life of Pi',
                'author' => 'Yann Martel',
                'published_year' => 2001,
                'summary' => 'A shipwrecked boy survives at sea with a Bengal tiger.',
                'available' => true,
            ],
            [
                'title' => 'The Road',
                'author' => 'Cormac McCarthy',
                'published_year' => 2006,
                'summary' => 'A father and son traverse a post-apocalyptic landscape.',
                'available' => false,
            ],
            [
                'title' => 'Beloved',
                'author' => 'Toni Morrison',
                'published_year' => 1987,
                'summary' => 'A haunting exploration of memory, motherhood, and slavery’s legacy.',
                'available' => true,
            ],
            [
                'title' => 'Invisible Man',
                'author' => 'Ralph Ellison',
                'published_year' => 1952,
                'summary' => 'An African American man’s search for identity and visibility.',
                'available' => true,
            ],
            [
                'title' => 'The Color Purple',
                'author' => 'Alice Walker',
                'published_year' => 1982,
                'summary' => 'Letters chart resilience, abuse, and sisterhood in the Deep South.',
                'available' => false,
            ],
            [
                'title' => 'The Handmaid\'s Tale',
                'author' => 'Margaret Atwood',
                'published_year' => 1985,
                'summary' => 'A theocratic regime subjugates women in a chilling dystopia.',
                'available' => true,
            ],
            [
                'title' => 'The Name of the Rose',
                'author' => 'Umberto Eco',
                'published_year' => 1980,
                'summary' => 'A Franciscan friar investigates murders in a medieval abbey.',
                'available' => false,
            ],
            [
                'title' => 'The Wind-Up Bird Chronicle',
                'author' => 'Haruki Murakami',
                'published_year' => 1994,
                'summary' => 'A surreal odyssey as a man searches for his missing wife.',
                'available' => true,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
